<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class CheckoutWebhookController extends Controller
{
    private const SECRET  = 'bzb-checkout-webhook-2026';
    private const USER_ID = 1;

    private const CATEGORY_MAP = [
        'fnb'        => ['f&b','makanan','minuman','restoran','cafe','kedai makan','food','fnb'],
        'retail'     => ['retail','kedai runcit','butik','pakaian','fashion','clothing'],
        'servis'     => ['servis','service','perkhidmatan','cleaning','workshop','repair'],
        'pendidikan' => ['pendidikan','tuisyen','kelas','education','tuition','akademi'],
        'kesihatan'  => ['kesihatan','kecantikan','beauty','clinic','klinik','spa','wellness'],
        'pembuatan'  => ['pembuatan','manufacturing','kilang','production','factory'],
        'berpremis'  => ['berpremis','premis'],
    ];

    public function handle(Request $request)
    {
        if ($request->header('X-Webhook-Secret') !== self::SECRET) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $businessName = trim($request->input('business_name', ''));
        $ownerName    = trim($request->input('owner_name', ''));
        $email        = trim($request->input('email', ''));
        $phone        = trim($request->input('phone', ''));
        $businessType = strtolower(trim($request->input('business_type', '')));
        $product      = $request->input('product', 'Audit Lite');
        $orderRef     = $request->input('order_ref', '');

        if (!$businessName) {
            return response()->json(['error' => 'Missing business_name'], 422);
        }

        $category = 'berpremis';
        foreach (self::CATEGORY_MAP as $key => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($businessType, $kw)) {
                    $category = $key;
                    break 2;
                }
            }
        }

        $notes = "Bayar melalui checkout online. Produk: {$product}. Ref: {$orderRef}.";

        $client = Client::create([
            'user_id'           => self::USER_ID,
            'name'              => $businessName,
            'owner_name'        => $ownerName,
            'email'             => $email,
            'phone'             => $phone,
            'business_category' => $category,
            'status'            => 'lead',
            'notes'             => $notes,
        ]);

        return response()->json(['ok' => true, 'client_id' => $client->id], 201);
    }
}
