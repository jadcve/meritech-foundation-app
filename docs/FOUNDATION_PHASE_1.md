# Meritech Foundation

Foundation is a Laravel base for new SaaS products. It contains no product domain.

## Phase 1

- Breeze Blade authentication
- Users and Spatie roles/permissions
- Generic tenants and tenant memberships
- Active tenant context resolved per request
- Foundation configuration

Tenant-specific application data must use the active context and fail closed when no tenant is resolved.

Product modules and domains are intentionally absent. The first consumer may define its own business vocabulary later.
