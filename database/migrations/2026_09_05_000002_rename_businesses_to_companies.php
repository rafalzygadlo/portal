<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS feed');

        if (Schema::hasTable('businesses')) {
            Schema::rename('businesses', 'companies');
        }

        if (Schema::hasTable('companies') && Schema::hasColumn('companies', 'business_hours')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->renameColumn('business_hours', 'company_hours');
            });
        }

        if (Schema::hasTable('business_user')) {
            Schema::rename('business_user', 'company_user');
        }

        foreach (['resources', 'services', 'reservations', 'booking_flows', 'resource_bookings'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'business_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('business_id', 'company_id');
                });
            }
        }

        if (Schema::hasTable('company_user') && Schema::hasColumn('company_user', 'business_id')) {
            Schema::table('company_user', function (Blueprint $table) {
                $table->renameColumn('business_id', 'company_id');
            });
        }

        foreach (['promotions' => 'promotable_type', 'favorites' => 'favoritable_type', 'comments' => 'commentable_type', 'images' => 'imageable_type'] as $tableName => $typeColumn) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, $typeColumn)) {
                DB::table($tableName)
                    ->where($typeColumn, 'App\\Models\\Business')
                    ->update([$typeColumn => 'App\\Models\\Company']);
            }
        }

        DB::statement(<<<'SQL'
            CREATE VIEW feed AS
            SELECT
                'article' AS type,
                articles.id AS item_id,
                articles.user_id,
                articles.title,
                articles.slug,
                articles.created_at,
                articles.updated_at,
                articles.deleted_at,
                EXISTS (
                    SELECT 1 FROM promotions
                    WHERE promotions.promotable_id = articles.id
                      AND promotions.promotable_type = 'App\Models\Article'
                      AND promotions.expires_at > CURRENT_TIMESTAMP
                ) AS is_promoted
            FROM articles
            WHERE articles.deleted_at IS NULL

            UNION ALL

            SELECT
                'todo' AS type,
                todos.id AS item_id,
                todos.user_id,
                todos.title,
                todos.slug,
                todos.created_at,
                todos.updated_at,
                todos.deleted_at,
                EXISTS (
                    SELECT 1 FROM promotions
                    WHERE promotions.promotable_id = todos.id
                      AND promotions.promotable_type = 'App\Models\Todo'
                      AND promotions.expires_at > CURRENT_TIMESTAMP
                ) AS is_promoted
            FROM todos
            WHERE todos.deleted_at IS NULL

            UNION ALL

            SELECT
                'company' AS type,
                companies.id AS item_id,
                NULL AS user_id,
                companies.name AS title,
                companies.subdomain AS slug,
                companies.created_at,
                companies.updated_at,
                companies.deleted_at,
                EXISTS (
                    SELECT 1 FROM promotions
                    WHERE promotions.promotable_id = companies.id
                      AND promotions.promotable_type = 'App\Models\Company'
                      AND promotions.expires_at > CURRENT_TIMESTAMP
                ) AS is_promoted
            FROM companies
            WHERE companies.deleted_at IS NULL

            UNION ALL

            SELECT
                'offer' AS type,
                offers.id AS item_id,
                offers.user_id,
                offers.title,
                offers.slug,
                offers.created_at,
                offers.updated_at,
                offers.deleted_at,
                EXISTS (
                    SELECT 1 FROM promotions
                    WHERE promotions.promotable_id = offers.id
                      AND promotions.promotable_type = 'App\Models\Offer'
                      AND promotions.expires_at > CURRENT_TIMESTAMP
                ) AS is_promoted
            FROM offers
            WHERE offers.deleted_at IS NULL
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS feed');

        foreach (['resources', 'services', 'reservations', 'booking_flows', 'resource_bookings'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'company_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('company_id', 'business_id');
                });
            }
        }

        if (Schema::hasTable('company_user')) {
            if (Schema::hasColumn('company_user', 'company_id')) {
                Schema::table('company_user', function (Blueprint $table) {
                    $table->renameColumn('company_id', 'business_id');
                });
            }
            Schema::rename('company_user', 'business_user');
        }

        if (Schema::hasTable('companies')) {
            if (Schema::hasColumn('companies', 'company_hours')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->renameColumn('company_hours', 'business_hours');
                });
            }
            Schema::rename('companies', 'businesses');
        }

        foreach (['promotions' => 'promotable_type', 'favorites' => 'favoritable_type', 'comments' => 'commentable_type', 'images' => 'imageable_type'] as $tableName => $typeColumn) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, $typeColumn)) {
                DB::table($tableName)
                    ->where($typeColumn, 'App\\Models\\Company')
                    ->update([$typeColumn => 'App\\Models\\Business']);
            }
        }
    }
};
