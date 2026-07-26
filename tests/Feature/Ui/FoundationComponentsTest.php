<?php

namespace Tests\Feature\Ui;

use App\Core\Tenancy\Models\TenantSettings;
use App\Core\Tenancy\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\Support\CreatesTenantMemberships;
use Tests\TestCase;

class FoundationComponentsTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_app_shell_uses_branding_manager_values(): void
    {
        $tenant = $this->createTenant(['name' => 'Foundation Tenant']);
        TenantSettings::query()->create([
            'tenant_id' => $tenant->getKey(),
            'primary_color' => '#123456',
            'secondary_color' => '#abcdef',
        ]);

        app(TenantContext::class)->set($tenant);

        $html = Blade::render('<x-foundation.layout.app-shell>Content</x-foundation.layout.app-shell>');

        $this->assertStringContainsString('--foundation-color-primary: #123456', $html);
        $this->assertStringContainsString('--foundation-color-secondary: #abcdef', $html);
        $this->assertStringContainsString('Foundation Tenant', $html);
        $this->assertStringContainsString('Content', $html);
    }

    public function test_app_shell_uses_safe_defaults_without_tenant_settings(): void
    {
        $html = Blade::render('<x-foundation.layout.app-shell>Public content</x-foundation.layout.app-shell>');

        $this->assertStringContainsString('--foundation-color-primary: #2563eb', $html);
        $this->assertStringContainsString('--foundation-color-secondary: #64748b', $html);
        $this->assertStringContainsString('Meritech Foundation', $html);
        $this->assertStringContainsString('Public content', $html);
    }

    public function test_guest_layout_uses_foundation_defaults_without_tenant(): void
    {
        $html = Blade::render('<x-guest-layout>Guest content</x-guest-layout>');

        $this->assertStringContainsString('<title>Meritech Foundation</title>', $html);
        $this->assertStringContainsString('Guest content', $html);
    }

    public function test_core_components_render_accessible_defaults(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-foundation.layout.breadcrumbs :items="[['label' => 'Home', 'url' => '/'], ['label' => 'Current']]" />
            <x-foundation.navigation.nav-item href="/dashboard" active>Dashboard</x-foundation.navigation.nav-item>
            <x-foundation.data.empty-state title="Nothing here" description="Try a different filter." />
            <x-foundation.forms.input label="Name" name="name" />
            <x-foundation.actions.icon-button label="Refresh">R</x-foundation.actions.icon-button>
            <x-foundation.feedback.alert title="Saved">Ready</x-foundation.feedback.alert>
        BLADE);

        $this->assertStringContainsString('aria-label="Breadcrumb"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('Nothing here', $html);
        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('aria-label="Refresh"', $html);
        $this->assertStringContainsString('role="status"', $html);
    }

    public function test_alpine_markup_keeps_accessibility_attributes(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-foundation.navigation.user-menu>
                <x-dropdown-link href="/profile">Profile</x-dropdown-link>
            </x-foundation.navigation.user-menu>
            <x-foundation.feedback.modal name="review-modal" title="Review modal">
                Modal body
            </x-foundation.feedback.modal>
        BLADE);

        $this->assertStringContainsString('aria-haspopup="menu"', $html);
        $this->assertStringContainsString('x-bind:aria-expanded="open.toString()"', $html);
        $this->assertStringContainsString('role="menu"', $html);
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('aria-modal="true"', $html);
        $this->assertStringContainsString('id="review-modal-title"', $html);
    }

    public function test_data_form_action_and_feedback_components_render(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-foundation.card>Card content</x-foundation.card>
            <x-foundation.stat-card label="Users" value="12" />
            <x-foundation.action-card title="Action" description="Reusable action." />
            <x-foundation.metric-card label="Metric" value="98%" meta="Stable" />
            <x-foundation.data.badge variant="success">Active</x-foundation.data.badge>
            <x-foundation.data.status-pill status="warning">Pending</x-foundation.data.status-pill>
            <x-foundation.data.search name="q" placeholder="Search records" />
            <x-foundation.forms.select label="State" name="state"><option>Open</option></x-foundation.forms.select>
            <x-foundation.forms.textarea label="Notes" name="notes">Body</x-foundation.forms.textarea>
            <x-foundation.forms.checkbox label="Enabled" name="enabled" />
            <x-foundation.forms.radio label="Default" name="choice" />
            <x-foundation.forms.switch label="Published" name="published" />
            <x-foundation.forms.validation-messages :messages="['Required']" />
            <x-foundation.actions.button>Save</x-foundation.actions.button>
            <x-foundation.feedback.toast message="Updated" />
            <x-foundation.feedback.skeleton-loader :lines="2" />
        BLADE);

        foreach (['Card content', 'Users', 'Action', 'Metric', 'Active', 'Pending', 'Search records', 'Open', 'Body', 'Enabled', 'Default', 'Published', 'Required', 'Save', 'Updated'] as $text) {
            $this->assertStringContainsString($text, $html);
        }
    }

    public function test_core_ui_does_not_require_react(): void
    {
        $package = json_decode(file_get_contents(base_path('package.json')), true);
        $dependencies = array_merge(
            $package['dependencies'] ?? [],
            $package['devDependencies'] ?? [],
        );

        $this->assertArrayNotHasKey('react', $dependencies);
        $this->assertArrayNotHasKey('react-dom', $dependencies);

        $islands = file_get_contents(resource_path('js/foundation/core/islands.js'));

        $this->assertStringContainsString('registerIsland', $islands);
        $this->assertStringNotContainsString('react', strtolower($islands));
    }
}
