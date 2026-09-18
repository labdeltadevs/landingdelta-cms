<?php

use App\Models\JobOpening;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('job_openings', 'slug')) {
            Schema::table('job_openings', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('title');
            });
        }

        JobOpening::query()->whereNull('slug')->orWhere('slug', '')->orderBy('id')->each(function (JobOpening $job): void {
            $base = Str::slug($job->title) ?: 'convocatoria';

            do {
                $slug = $base.'-'.Str::lower(Str::random(4));
            } while (JobOpening::query()->where('slug', $slug)->exists());

            $job->updateQuietly(['slug' => $slug]);
        });

        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS job_openings_slug_unique ON job_openings (slug)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('job_openings', 'slug')) {
            Schema::table('job_openings', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
