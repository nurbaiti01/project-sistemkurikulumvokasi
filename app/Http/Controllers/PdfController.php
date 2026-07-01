<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KontrakKuliah;
use App\Models\Kurikulum;
use App\Models\Matakuliah;
use App\Models\RealisasiPengajaran;
use App\Models\RealisasiPengajaranDetail;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use App\Models\Rps;
use App\Models\PivotCplCpmkMk;
use App\Models\CapaianPembelajaranMatakuliah;
use App\Services\KurikulumTreeBuilder;
use Illuminate\Support\Facades\Cache;
class PdfController extends Controller
{
    public function previewKontrakKuliah(int $id)
    {
        $kontrak = $this->getKontrakKuliahData($id);

        return Pdf::loadView('pdf.kontrak-kuliah', compact('kontrak'))
            ->setPaper('a4', 'portrait')
            ->stream('kontrak-kuliah.pdf');
    }

    public function previewRealisasiAjar(int $id)
    {
        $data = $this->getRealisasiPengajaranData($id);
        return Pdf::loadView('pdf.realisasi-pembelajaran', $data)
            ->setPaper('a4', 'portrait')
            ->stream('realisasi-pembelajaran.pdf');
    }

    public function previewRps(int $id)
    {
        $rpsData = $this->getRpsData($id);
        // dd($rpsData);
        $pdfCover = Pdf::loadView('pdf.cover-rps', $rpsData)
            ->setPaper('a4', 'portrait');
        $pdfAporoval = Pdf::loadView('pdf.approval-rps', $rpsData)
            ->setPaper('a4', 'landscape');
        $pdfContent = Pdf::loadView('pdf.rencana-pembelajaran-semester', $rpsData)
            ->setPaper('a4', 'landscape');
        $pdfRancangaNilai = Pdf::loadView('pdf.rancangan-nilai-rps', $rpsData)
            ->setPaper('a4', 'portrait');
        $merger = new Merger;
        $merger->addRaw($pdfCover->output());
        $merger->addRaw($pdfAporoval->output());
        $merger->addRaw($pdfContent->output());
        $merger->addRaw($pdfRancangaNilai->output());

        return response($merger->merge())
            ->header('Content-Type', 'application/pdf');
    }

    public function previewKurikulum(int $id)
    {
        $kurikulum = Kurikulum::with(['programStudis', 'wadirApproval', 'creator'])->findOrFail($id);

        $tree = Cache::remember(
            "kurikulum_tree_{$id}",
            now()->addHours(6), // atau addDays(1)
            function () use ($kurikulum) {
                return (new KurikulumTreeBuilder($kurikulum))->build();
            }
        );

        return Pdf::loadView('pdf.kurikulum', [
            'kurikulum' => $kurikulum,
            'tree' => $tree,
        ])
            ->setPaper('a4', 'portrait')
            ->stream('kurikulum.pdf');
    }
    protected function remapKurikulum($data)
    {
        dd($data);
    }
    protected function getRpsData($id)
    {
        $data = Rps::with(['matakuliah', 'referensis', 'pertemuans', 'dosen', 'programStudi', 'rpsApprovals', 'penilaians'])->findOrFail($id);

        return [
            'cover' => $this->mapRpsCover($data),
            'approvals' => $data->rpsApprovals,
            'identitas' => $this->mapRpsIdentitas($data),
            'matakuliah' => $this->mapRpsMatakuliah($data),
            'cpl_cpmk_matrix' => $this->matriksCplCpmkData($data->matakuliah_id, $data->program_studi_id),
            'pertemuans' => $data->pertemuans->map(fn($item) => $this->mapRpsPertemuan($item)),
            'referensis' => $data->referensis,
            'penilaians' => $this->mapRpsPenilaian($data->penilaians),

        ];
    }

    protected function mapRpsPenilaian($dataDb)
    {
        $penilaians = collect($dataDb)
            ->groupBy('jenis_penilaian')
            ->map(function ($items) {
                return [
                    'jenis' => $items->first()->jenis_penilaian,
                    'kelompok' => $items->first()->kelompok,
                    'persentase' => $items->first()->persentase_penilaian,
                    'cpmk' => $items->keyBy('cpmk_id')->map(fn($i) => $i->bobot_cpmk),
                ];
            })
            ->values();
        return $penilaians;
    }

    protected function mapRpsCover(Rps $data)
    {
        return [
            'kode_mk' => $data->matakuliah->code,
            'nama_mk' => $data->matakuliah->name,
            'program_studi' => $data->programStudi->name,
            'tahun_akademik' => $data->academic_year,
            'semester' => $data->matakuliah->semester == 1 ? 'Ganjil' : 'Genap',
        ];
    }

    protected function mapRpsIdentitas(Rps $data)
    {
        return [
            'dosen' => $data->dosen->name,
            'program_studi' => $data->programStudi->name,
            'class' => $data->class,
            'tahun_akademik' => $data->academic_year,
            'revision' => $data->revision,
            'cpmk_bobot' => $data->cpmk_bobot,
            'learning_method' => $data->learning_method,
            'learning_experience' => $data->learning_experience,
        ];
    }

    protected function mapRpsMatakuliah(Rps $data)
    {
        return [
            'kode_mk' => $data->matakuliah->code,
            'nama_mk' => $data->matakuliah->name,
            'semester' => $data->matakuliah->semester,
            'jumlah_sks' => $data->matakuliah->sks,
            'description' => $data->matakuliah->description,
        ];
    }

    protected function mapRpsPertemuan($pertemuan)
    {
        return [
            'pertemuan_ke' => $pertemuan->pertemuan_ke,
            'materi_ajar' => $pertemuan->materi_ajar,
            'cpmk' => $this->rpsDetailCpmk($pertemuan->cpmk_id),
            'bobot_cpmk' => $pertemuan->bobots,
            'indikator_penilaian' => $pertemuan->indikator,
            'bentuk_pembelajaran' => $pertemuan->bentuk_pembelajaran,
            'alokasi_waktu' => $pertemuan->alokasi,
            'rancangan_penilaian' => is_string($pertemuan->rancangan_penilaian)
                ? json_decode($pertemuan->rancangan_penilaian, true)
                : (array) $pertemuan->rancangan_penilaian,
        ];
    }

    protected function rpsDetailCpmk($id)
    {
        return CapaianPembelajaranMatakuliah::where('id', $id)->first();
    }
    protected function matriksCplCpmkData($mkId, $prodiId)
    {
        $kurikulum = $this->getPublishedKurikulumByProdi(
            $prodiId
        );
        $matakuliah = Matakuliah::with([
            'MkCpmk' => fn($q) =>
                $q->where('kurikulum_id', $kurikulum->id)->with('cpmk'),

            'MkCpl' => fn($q) =>
                $q->where('kurikulum_id', $kurikulum->id)->with('cpl'),
        ])
            ->find($mkId);

        if ($matakuliah) {
            $cplList = $matakuliah->MkCpl->pluck('cpl', 'cpl_id');
            $cpmkList = $matakuliah->MkCpmk->pluck('cpmk', 'cpmk_id');
            $matakuliah->cplMap = $matakuliah->MkCpl->keyBy('cpl_id');
            $matakuliah->cpmkMap = $matakuliah->MkCpmk->keyBy('cpmk_id');
        }

        $pivot = PivotCplCpmkMk::query()
            ->where('mk_id', $mkId)
            ->get()
            ->groupBy('cpmk_id')
            ->map(
                fn($rows) =>
                $rows->pluck('cpl_id')->flip()->map(fn() => true)
            );

        $matrix = [];

        foreach ($cplList as $cplId => $cpl) {
            foreach ($cpmkList as $cpmkId => $cpmk) {
                $matrix[$cpmkId][$cplId] = isset($pivot[$cpmkId][$cplId]);
            }
        }

        $matriksCplCpmk = [
            'cpmk' => $matakuliah->cpmkMap->map(fn($item) => [
                'id' => $item->cpmk->id,
                'code' => $item->cpmk->code,
                'label' => $item->cpmk->description,
            ]),
            'cpl' => $matakuliah->cplMap->map(fn($item) => [
                'id' => $item->cpl->id,
                'code' => $item->cpl->code,
                'label' => $item->cpl->description,
            ]),
            'matrix' => $matrix
        ];
        return $matriksCplCpmk;
    }



    /* ================= DATA PROVIDER ================= */

    protected function getRealisasiPengajaranData(int $id): array
    {
        $realisasi = RealisasiPengajaran::with(['details', 'programStudi', 'matakuliah', 'dosen', 'evaluasis', 'referensis', 'metodes', 'approvals'])
            ->findOrFail($id);

        return [
            'realisasi' => $this->mapRealisasi($realisasi),
            'pertemuans' => $this->mapPertemuans($realisasi->details),
            'evaluasi' => $realisasi->evaluasis,
            'referensis' => $realisasi->referensis,
            'metodes' => $realisasi->metodes,
        ];
    }

    /* ================= TRANSFORMER ================= */

    protected function mapRealisasi(RealisasiPengajaran $data): array
    {
        return [
            'kode_mk' => $data->matakuliah->code,
            'nama_mk' => $data->matakuliah->name,
            'program_studi' => $data->programStudi->name,
            'kelas' => $data->kelas,
            'tahun_akademik' => $data->tahun_akademik,
            'tujuan_instruksional_umum' => $data->tujuan_instruksional_umum,
            'semester' => $data->semester,
            'jumlah_sks' => $data->jumlah_sks,
        ];
    }

    protected function mapPertemuans($details)
    {
        return $details->map(fn($item) => [
            'pertemuan_ke' => $item->pertemuan_ke,
            'tanggal' => Carbon::parse($item->tanggal)->format('Y-m-d'),
            'pokok_bahasan' => $item->pokok_bahasan,
            'jam' => $item->jam,
            'paraf' => $item->paraf,
        ]);
    }

    /* ================== DATA PROVIDER ================== */

    protected function getKontrakKuliahData(int $id): KontrakKuliah
    {
        $kontrak = KontrakKuliah::with([
            'matakuliah.MkCpmk.cpmk',
            'dosen',
            'programStudis',
        ])->findOrFail($id);

        $kurikulum = $this->getPublishedKurikulumByProdi($kontrak->prodi_id);

        $kontrak->cpmk = $this->mapCpmk(
            $kontrak->matakuliah->MkCpmk
                ->where('kurikulum_id', $kurikulum->id)
        );

        return $kontrak;
    }

    protected function getPublishedKurikulumByProdi(int $prodiId): Kurikulum
    {
        return Kurikulum::published()
            ->byProdi($prodiId)
            ->firstOrFail();
    }

    /* ================== TRANSFORMER ================== */

    protected function mapCpmk($mkCpmk)
    {
        $letters = range('a', 'z');

        return $mkCpmk->values()->map(function ($item, $index) use ($letters) {
            return [
                'prefix' => $letters[$index] ?? $index + 1,
                'code' => $item->cpmk->code,
                'label' => $item->cpmk->description,
            ];
        });
    }

}
