<?php

namespace App\Services;

use App\Models\Kurikulum;

class KurikulumTreeBuilder
{
    protected Kurikulum $kurikulum;

    public function __construct(Kurikulum $kurikulum)
    {
        $this->kurikulum = $kurikulum;
    }

    /**
     * ==========================================
     * Build Tree
     * ==========================================
     */
    public function build(): array
    {
        $tree = [];

        /*
        |--------------------------------------------------------------------------
        | Helper node (NO reference)
        |--------------------------------------------------------------------------
        */
        $node = fn($id, $type, $label, $desc = '') => [
            'id' => $id,
            'type' => $type,
            'label' => $label,
            'desc' => $desc,
            'children' => [],
        ];

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ CPL ROOT
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotPlCpl()
                ->with('cpl:id,code,description')
                ->get()
            as $pivot
        ) {
            $cplId = $pivot->cpl_id;

            $tree[$cplId] ??= $node(
                $cplId,
                'cpl',
                $pivot->cpl->code,
                $pivot->cpl->description
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ CPL → PL
        |--------------------------------------------------------------------------
        */
        
        foreach (
            $this->kurikulum
                ->pivotPlCpl()
                ->with(['cpl:id', 'pl:id,code,name,description'])
                ->get()
            as $pivot
        ) {
            // dd($pivot->pl->code);
            $tree[$pivot->cpl_id]['children'][] =
                $node($pivot->pl_id, 'pl', $pivot->pl?->code.' : '.$pivot->pl->name, $pivot->pl->description);
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ CPL → BK
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotCplBk()
                ->with(['cpl:id', 'bk:id,code,name,description'])
                ->get()
            as $pivot
        ) {
            $tree[$pivot->cpl_id]['children'][] =
                $node($pivot->bk_id, 'bk', $pivot->bk->code.' : '.$pivot->bk->name, $pivot->bk->description);
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ BK → MK
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotBkMk()
                ->with(['bk:id', 'mk:id,code,name'])
                ->get()
            as $pivot
        ) {
            foreach ($tree as &$cpl) {
                foreach ($cpl['children'] as &$child) {
                    if ($child['type'] === 'bk' && $child['id'] === $pivot->bk_id) {
                        $child['children'][] =
                            $node($pivot->mk_id, 'mk', $pivot->mk->code.' : '.$pivot->mk->name);
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ CPL → MK (langsung)
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotCplMk()
                ->with(['cpl:id', 'mk:id,code,name'])
                ->get()
            as $pivot
        ) {
            $tree[$pivot->cpl_id]['children'][] =
                $node($pivot->mk_id, 'mk', $pivot->mk->code.' : '.$pivot->mk->name);
        }

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ MK → CPMK
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotCpmkMk()
                ->with(['mk:id', 'cpmk:id,code'])
                ->get()
            as $pivot
        ) {
            foreach ($tree as &$cpl) {
                foreach ($cpl['children'] as &$child) {
                    if ($child['type'] === 'mk' && $child['id'] === $pivot->mk_id) {
                        $child['children'][] =
                            $node($pivot->cpmk_id, 'cpmk', $pivot->cpmk->code);
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7️⃣ CPMK → SubCPMK
        |--------------------------------------------------------------------------
        */
        foreach (
            $this->kurikulum
                ->pivotCpmkSubCpmk()
                ->with(['cpmk:id', 'subcpmk:id,code'])
                ->get()
            as $pivot
        ) {
            foreach ($tree as &$cpl) {
                foreach ($cpl['children'] as &$child) {
                    if ($child['type'] === 'mk') {
                        foreach ($child['children'] as &$cpmk) {
                            if ($cpmk['id'] === $pivot->cpmk_id) {
                                $cpmk['children'][] =
                                    $node(
                                        $pivot->subcpmk_id,
                                        'subcpmk',
                                        $pivot->subcpmk->code
                                    );
                            }
                        }
                    }
                }
            }
        }

       return $this->sanitizeTree(array_values($tree));
    }

    protected function sanitizeTree(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            if (!isset($node['id'], $node['type'], $node['label'])) {
                continue;
            }

            if (!empty($node['children'])) {
                $node['children'] = $this->sanitizeTree($node['children']);
            }

            $result[] = $node;
        }

        return $result;
    }

}
