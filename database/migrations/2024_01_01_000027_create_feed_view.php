<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS feed');
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
    }
};
