<?php

class ViewLowongan implements KontrakView
{
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '')
    {
        $this->skinPath = $skinPath ?: __DIR__ . '/../templateLowongan/skin.html';
        $this->formPath = $formPath ?: __DIR__ . '/../templateLowongan/form.html';
    }

    public function renderDaftar(string $tableRowsHtml, int $totalData): string
    {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string) $totalData,
        ];
        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    public function renderForm(string $title, string $action, array $formData = []): string
    {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}' => htmlspecialchars($title),
            '{{ACTION}}' => htmlspecialchars($action),
            '{{ID}}' => htmlspecialchars($formData['id' ?? '']),
            '{{NAMA}}' => htmlspecialchars($formData['nama'] ?? ''),
            '{{ASALPERUSAHAAN}}' => htmlspecialchars($formData['asalPerusahaan'] ?? ''),
            '{{MINIMALSKORTES}}' => htmlspecialchars((string) $formData['minimalSkorTes'] ?? 0),
        ];
        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}