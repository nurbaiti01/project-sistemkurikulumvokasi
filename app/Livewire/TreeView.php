<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
class TreeView extends Component
{
    public string $matrixMode = '';
    public array $originalTree = [];
    public array $tree = [];

    public array $expanded = [];
    public array $selected = [];

    public function mount(array $tree)
    {
        $this->originalTree = $tree;
        // $this->applyMatrixFilter();
    }
    #[On('applyMatrixFilter')]
    public function applyMatrixFilter(string $mode): void
    {
        $this->matrixMode = $mode;
        $this->tree = match ($mode) {
            'cpl-pl' => $this->extractFromCpl(['pl']),
            'cpl-bk' => $this->extractFromCpl(['bk']),
            'cpl-mk' => $this->extractFromCpl(['mk']),
            'cpl-bk-mk' => $this->extractFromCpl(['bk', 'mk']),
            'cpl-cpmk-mk' => $this->extractFromCpl(['mk', 'cpmk']),

            'bk-mk' => $this->reRoot('bk', 'mk'),
            'mk-cpmk' => $this->reRoot('mk', 'cpmk'),
            'cpmk-subcpmk' => $this->reRoot('cpmk', 'subcpmk'),

            default => $this->originalTree,
        };
    }

    protected function reRoot(string $rootType, string $childType): array
    {
        $result = [];

        $walk = function ($nodes) use (&$walk, &$result, $rootType, $childType) {
            foreach ($nodes as $node) {
                if ($node['type'] === $rootType) {
                    $new = $node;
                    $new['children'] = collect($node['children'])
                        ->filter(fn($c) => $c['type'] === $childType)
                        ->values()
                        ->all();

                    if ($new['children']) {
                        $result[] = $new;
                    }
                }

                if (!empty($node['children'])) {
                    $walk($node['children']);
                }
            }
        };

        $walk($this->originalTree);

        return $result;
    }

    protected function extractFromCpl(array $allowed): array
    {
        return collect($this->originalTree)->map(function ($cpl) use ($allowed) {
            $cpl['children'] = collect($cpl['children'])
                ->filter(fn($child) => in_array($child['type'], $allowed))
                ->values()
                ->all();

            return $cpl;
        })->values()->all();
    }

    // protected function filterTree(array $allowedTypes): array
    // {
    //     $filter = function (array $nodes) use (&$filter, $allowedTypes) {
    //         $result = [];

    //         foreach ($nodes as $node) {
    //             if (!in_array($node['type'], $allowedTypes)) {
    //                 continue;
    //             }

    //             $newNode = $node;
    //             $newNode['children'] = [];

    //             if (!empty($node['children'])) {
    //                 $children = $filter($node['children']);

    //                 // hanya attach child jika level berikutnya valid
    //                 if ($children) {
    //                     $newNode['children'] = $children;
    //                 }
    //             }

    //             $result[] = $newNode;
    //         }

    //         return $result;
    //     };

    //     return $filter($this->originalTree);
    // }

    public function toggle(string $key): void
    {
        $this->expanded[$key] = !($this->expanded[$key] ?? false);
    }

    public function toggleSelect(string $type, int $id): void
    {
        if (isset($this->selected[$type][$id])) {
            unset($this->selected[$type][$id]);
        } else {
            $this->selected[$type][$id] = true;
        }
    }
    #[On('expandAll')]
    public function expandAll()
    {
        $this->expanded = [];

        $walk = function (array $nodes) use (&$walk) {
            foreach ($nodes as $node) {
                if (!isset($node['type'], $node['id'])) {
                    continue;
                }

                $key = "{$node['type']}-{$node['id']}";
                $this->expanded[$key] = true;

                if (!empty($node['children'])) {
                    $walk($node['children']);
                }
            }
        };

        $walk($this->tree);
    }
    #[On('collapseAll')]
    public function collapseAll()
    {
        $this->expanded = [];
    }

    public function render()
    {
        return view('livewire.tree-view');
    }
}
