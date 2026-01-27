<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\PurchasedProduct;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->all();

        if ($event['type'] === 'checkout.session.completed') {
            $session = $event['data']['object'];
            $user = User::where('email', $session['customer_email'])->first();
            if ($user) {
                // Gauk produktus iš krepšelio
                $products = $user->cartProducts;
                // Sugeneruok PDF
                $pdf = Pdf::loadView('users.profile_pdf', [
                    'user' => $user,
                    'purchasedProducts' => $products
                ]);
                $pdfPath = 'invoices/invoice_' . uniqid() . '.pdf';
                \Storage::disk('public')->put($pdfPath, $pdf->output());

                \Log::info('Stripe insert debug', [
                    'user_id' => $user->id ?? null,
                    'pdfPath' => $pdfPath ?? null,
                    'products' => $products->map(function($product) {
                return [
                        'id' => $product->id ?? null,
                        'name' => $product->name ?? null,
                        'price' => $product->price ?? null,
                        ];
                    })
                ]);
                    // Priskirk PDF prie kiekvieno produkto
                foreach ($products as $product) {
                    PurchasedProduct::create([
                        'user_id' => $user->id,
                        'game_id' => $product->game_id, // <-- svarbu!
                        'pdf_path' => $pdfPath,
                        'name' => $product->name,
                        'price' => $product->price,
                    ]);
                }

                // Išvalyk krepšelį
                $user->cartProducts()->delete();
            }
        }
        return response('Webhook handled', 200);
    }
}