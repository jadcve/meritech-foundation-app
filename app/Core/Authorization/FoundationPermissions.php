<?php

namespace App\Core\Authorization;

final class FoundationPermissions
{
    public const TENANT_VIEW = 'tenant.view';

    public const TENANT_UPDATE = 'tenant.update';

    public const MEMBERS_VIEW = 'members.view';

    public const MEMBERS_INVITE = 'members.invite';

    public const MEMBERS_UPDATE = 'members.update';

    public const MEMBERS_REMOVE = 'members.remove';

    public const ROLES_VIEW = 'roles.view';

    public const ROLES_MANAGE = 'roles.manage';

    public const SETTINGS_VIEW = 'settings.view';

    public const SETTINGS_UPDATE = 'settings.update';

    public const OWNER = 'owner';

    public const ADMIN = 'admin';

    public const MEMBER = 'member';

    public const VIEWER = 'viewer';

    public static function permissions(): array
    {
        return [
            self::TENANT_VIEW,
            self::TENANT_UPDATE,
            self::MEMBERS_VIEW,
            self::MEMBERS_INVITE,
            self::MEMBERS_UPDATE,
            self::MEMBERS_REMOVE,
            self::ROLES_VIEW,
            self::ROLES_MANAGE,
            self::SETTINGS_VIEW,
            self::SETTINGS_UPDATE,
        ];
    }

    public static function roles(): array
    {
        return [
            self::OWNER,
            self::ADMIN,
            self::MEMBER,
            self::VIEWER,
        ];
    }

    public static function rolePermissions(): array
    {
        return [
            self::OWNER => self::permissions(),
            self::ADMIN => [
                self::TENANT_VIEW,
                self::TENANT_UPDATE,
                self::MEMBERS_VIEW,
                self::MEMBERS_INVITE,
                self::MEMBERS_UPDATE,
                self::MEMBERS_REMOVE,
                self::ROLES_VIEW,
                self::SETTINGS_VIEW,
                self::SETTINGS_UPDATE,
            ],
            self::MEMBER => [
                self::TENANT_VIEW,
                self::MEMBERS_VIEW,
                self::SETTINGS_VIEW,
            ],
            self::VIEWER => [
                self::TENANT_VIEW,
                self::MEMBERS_VIEW,
                self::SETTINGS_VIEW,
            ],
        ];
    }
}
