<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class InstallRouteTest extends TestCase
{
    public function test_install_page_is_available(): void
    {
        $response = $this->get('/install');

        $response
            ->assertOk()
            ->assertViewIs('admin::install.index')
            ->assertSee('Nawat CMS')
            ->assertSee('نواة', false);
    }

    public function test_public_path_points_to_project_root(): void
    {
        $this->assertSame(dirname(base_path()), public_path());
    }
}
