<?php
namespace Adeliom\WP\Extensions\Blocks;


class AbstractBlockPHP extends Block implements InitializableInterface
{
    public function fileExtension(): string
    {
        return '.php';
    }

    public function isValid(): bool
    {
        return true;
    }

    public function renderBlockCallback(array $block): void
    {
        $frontend = apply_filters(
            'acf_gutenblocks/render_block_frontend_path',
            "{$this->dir}/views/frontend{$this->fileExtension()}",
            $this
        );

        if (file_exists($frontend)) {
            $path = $frontend;
        } else {
            $path = locate_template($frontend);
        }

        if (empty($path)) {
            return;
        }

        $block['slug'] = str_replace('acf/', '', $block['name']);
        $block['classes'] = sanitize_html_class([
            $block['slug'],
            $block['className'] ?? '',
            $block['align'] ?? '',
        ]);

        $controller = $this;

        extract($this->with());

        ob_start();

        // TODO: Check for remote file inclusion (WP VIP).
        // phpcs:disable WordPressVIPMinimum.Files.IncludingFile.IncludingFile
        include apply_filters('acf_gutenblocks/render_block_html', $path, $controller);
        // phpcs:enable

        $html = ob_get_clean();

        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        echo apply_filters('acf_gutenblocks/render_block_html_output', $html, $controller);
        // phpcs:enable
    }
}
