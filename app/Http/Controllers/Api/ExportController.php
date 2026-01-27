<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use XMLWriter;

class ExportController extends Controller
{
    /**
     * GET /api/exports/games.xml
     * Optional query:
     *   - q=... (jei nori eksportuoti pagal paiešką)
     */
    public function games(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $gamesQuery = Game::query()->with(['platforms', 'genres']);

        if ($q !== '') {
            $gamesQuery->where('name', 'like', '%' . $q . '%');
        }

        $games = $gamesQuery->orderBy('name')->get();

        $xw = new XMLWriter();
        $xw->openMemory();
        $xw->startDocument('1.0', 'UTF-8');
        $xw->setIndent(true);

        $xw->startElement('games_export');
        $xw->writeAttribute('generated_at', now()->toIso8601String());
        $xw->writeAttribute('count', (string) $games->count());

        foreach ($games as $g) {
            $xw->startElement('game');

            $xw->writeElement('id', (string) $g->id);
            $xw->writeElement('name', (string) $g->name);
            $xw->writeElement('slug', (string) $g->slug);
            $xw->writeElement('price', (string) $g->price);
            $xw->writeElement('image', (string) ($g->image ?? ''));
            $xw->writeElement('description', (string) ($g->description ?? ''));

            $xw->startElement('platforms');
            foreach ($g->platforms as $p) {
                $xw->startElement('platform');
                $xw->writeAttribute('id', (string) $p->id);
                $xw->text((string) $p->name);
                $xw->endElement();
            }
            $xw->endElement();

            $xw->startElement('genres');
            foreach ($g->genres as $ge) {
                $xw->startElement('genre');
                $xw->writeAttribute('id', (string) $ge->id);
                $xw->text((string) $ge->name);
                $xw->endElement();
            }
            $xw->endElement();

            $xw->endElement(); // game
        }

        $xw->endElement(); // games_export
        $xw->endDocument();

        $xml = $xw->outputMemory();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="games.xml"');
    }
}
