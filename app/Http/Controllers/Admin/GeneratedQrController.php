<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratedQr;
use App\Models\QrCategory;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneratedQrController extends Controller
{
    public function index()
    {
        $categories = QrCategory::where('status',1)->get();
        $records    = GeneratedQr::with('category')->latest()->get();

        return view('admin.qr_generate.index', compact('categories','records'));
    }

    // CREATE + GENERATE QR
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:qr_categories,id',
            'date'        => 'required|date'
        ]);

        $cat = QrCategory::findOrFail($request->category_id);

        $payload = [
            'category_id'   => $cat->id,
            'category_name' => $cat->name,
            'date'          => $request->date,
        ];

        GeneratedQr::create([
            'category_id' => $cat->id,
            'date'        => $request->date,
            'qr_data'     => json_encode($payload)
        ]);

        return back()->with('success','QR generated successfully');
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:qr_categories,id',
            'date'        => 'required|date'
        ]);

        $cat = QrCategory::findOrFail($request->category_id);

        $payload = [
            'category_id'   => $cat->id,
            'category_name' => $cat->name,
            'date'          => $request->date,
        ];

        GeneratedQr::where('id',$id)->update([
            'category_id' => $cat->id,
            'date'        => $request->date,
            'qr_data'     => json_encode($payload)
        ]);

        return back()->with('success','QR updated');
    }

    // DELETE
    public function destroy($id)
    {
        GeneratedQr::destroy($id);
        return back()->with('success','QR deleted');
    }

    // QR IMAGE HELPER
    public static function makeQr($data)
    {
        return base64_encode(
            QrCode::format('png')
                ->size(250)
                ->margin(2)
                ->generate($data)
        );
    }
}
