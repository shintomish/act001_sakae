<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

function normalize_filename($filename)
{
    // 前後の空白を除去
    $clean = trim($filename);

    // 複数の半角スペースを1個に
    $clean = preg_replace('/\s+/', ' ', $clean);

    // 複数の全角スペースを1個に
    $clean = preg_replace('/　+/', '　', $clean);

    // Windows禁止文字を除去
    $clean = preg_replace('/[\\\\\\/\\:\\*\\?\\"<>\\|]/', '_', $clean);

    return $clean;
}

function preview_rename_all_files_in_userdata()
{
    $root = storage_path('app/userdata');
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $changes = [];

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $dir = $file->getPath();
            $oldName = $file->getFilename();
            $newName = normalize_filename($oldName);

            if ($oldName !== $newName) {
                $changes[] = [
                    'dir' => $dir,
                    'old' => $oldName,
                    'new' => $newName,
                ];
            }
        }
    }

    if (empty($changes)) {
        echo "✅ 修正が必要なファイルはありませんでした。\n";
        Log::info("✅ 修正が必要なファイルはありませんでした。");
    } else {
        echo "🔍 以下のファイルが修正対象です（dry-run：実際の変更なし）:\n\n";
        foreach ($changes as $c) {
            echo "📁 {$c['dir']}\n";
            echo "   → {$c['old']}  →  {$c['new']}\n\n";
            Log::info("Preview Rename: {$c['dir']}/{$c['old']} → {$c['new']}");
        }
        echo "--------------------------------------------\n";
        echo "📝 ログは storage/logs/laravel.log に出力しました。\n";
    }
}
