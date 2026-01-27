use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // description jau yra, todėl pridedam tik tai ko trūksta
        if (!Schema::hasColumn('games', 'rawg_id')) {
            Schema::table('games', function (Blueprint $table) {
                $table->unsignedBigInteger('rawg_id')->nullable()->after('id');
                $table->unique('rawg_id');
            });
        }

        if (!Schema::hasColumn('games', 'description')) {
            Schema::table('games', function (Blueprint $table) {
                $table->longText('description')->nullable()->after('image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('games', 'rawg_id')) {
            Schema::table('games', function (Blueprint $table) {
                // jei unique indexas turi kitą pavadinimą – gali reikėti pakoreguoti
                $table->dropUnique(['rawg_id']);
                $table->dropColumn('rawg_id');
            });
        }

        if (Schema::hasColumn('games', 'description')) {
            // jei description buvo prieš šitą migraciją – NEDĖK drop, nes ištrinsi seną lauką
            // palik tuščią arba išimk šitą bloką
        }
    }
};
