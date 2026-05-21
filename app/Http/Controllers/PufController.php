<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostItem;
use App\Models\PostItemsCat;
use App\Services\LegacyFileService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PufController extends Controller
{
    protected $legacyFileService;

    public function __construct(LegacyFileService $legacyFileService)
    {
        $this->legacyFileService = $legacyFileService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_title'        => 'required|string|max:255',
            'post_description'  => 'nullable|string',
            'post_description2' => 'nullable|string',
            'post_survey'       => 'required|in:Updating,ENNS,NNS,RNAS',
            'post_type'         => 'required|in:biochemical,maternal,food,socio,anthrop,iycf,dietary,dietary_indiv,socio_dem,clinical,socio1,food1',
            'post_year'         => 'required|integer|min:1900|max:2100',
            'date_pub'          => 'nullable|string|max:255',
            'survey_option'     => 'required|in:existing,new',
            'existing_survey'   => 'required_if:survey_option,existing|nullable|exists:post_items_cats,id',
            'new_survey_name'   => 'required_if:survey_option,new|nullable|string|max:255',
            'pdf_path'          => 'nullable|file|mimes:pdf|max:10240',
            'puf_file'          => 'nullable|file|mimes:zip,rar|max:102400',
        ]);

        try {
            DB::beginTransaction();

            // Handle survey category
            if ($validated['survey_option'] === 'new') {
                $survey = PostItemsCat::create([
                    'cat_name'     => 'PUF',
                    'sub_category' => $validated['new_survey_name'],
                    'value'        => $validated['post_year'],
                ]);
                $surveySubCategory = $validated['new_survey_name'];
            } else {
                $survey = PostItemsCat::findOrFail($validated['existing_survey']);
                $surveySubCategory = $survey->sub_category;
            }

            // Prepare post_items data
            $data = [
                'post_title'        => $validated['post_title'],
                'post_description'  => $validated['post_description'] ?? '',
                'post_description2' => $validated['post_description2'] ?? '',
                'post_url'          => '',
                'post_type'         => $validated['post_type'],
                'post_survey'       => $validated['post_survey'],
                'post_year'         => $validated['post_year'],
                'date_pub'          => $validated['date_pub'] ?? 'NA',
                'post_cat'          => 'PUF',
                'status'            => '1',
                'pic_file'          => 'NA',
                'pdf_path'          => '',
            ];

            // // Handle image upload
            // if ($request->hasFile('pic_file')) {
            //     $imagePath = $this->legacyFileService->uploadImage(
            //         $request->file('pic_file'),
            //         'PUF'
            //     );
            //     if ($imagePath) {
            //         $data['pic_file'] = $imagePath;
            //     }
            // }

            // Handle PDF upload
            if ($request->hasFile('pdf_path')) {
                $pdfPath = $this->legacyFileService->uploadPdf(
                    $request->file('pdf_path'),
                    'PUF'
                );
                if ($pdfPath) {
                    $data['pdf_path'] = $pdfPath;
                }
            }

            // Create post item
            $postItem = PostItem::create($data);

            // Handle ZIP/RAR upload to puf_csv
            if ($request->hasFile('puf_file')) {
                $pufPath = $this->legacyFileService->uploadPuf(
                    $request->file('puf_file'),
                    $validated['post_title'],
                    $validated['post_year'],
                    $surveySubCategory
                );

                if ($pufPath) {
                    DB::table('puf_csv')->insert([
                        'name'         => $validated['post_title'],
                        'year'         => $validated['post_year'],
                        'file_path'    => $pufPath,
                        'post_item_id' => $postItem->id,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'PUF item created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PUF creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create PUF item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $postItem = PostItem::findOrFail($id);
            $pufCsv = DB::table('puf_csv')->where('post_item_id', $id)->first();
            $surveys = PostItemsCat::where('cat_name', 'PUF')->get();

            return response()->json([
                'post_item' => $postItem,
                'puf_csv'   => $pufCsv,
                'surveys'   => $surveys,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'post_title'        => 'required|string|max:255',
            'post_description'  => 'nullable|string',
            'post_description2' => 'nullable|string',
            'post_survey'       => 'required|in:Updating,ENNS,NNS,RNAS',
            'post_type'         => 'required|in:biochemical,maternal,food,socio,anthrop,iycf,dietary,dietary_indiv,socio_dem,clinical,socio1,food1',
            'post_year'         => 'required|integer|min:1900|max:2100',
            'date_pub'          => 'nullable|string|max:255',
            'survey_option'     => 'required|in:existing,new',
            'existing_survey'   => 'required_if:survey_option,existing|nullable|exists:post_items_cats,id',
            'new_survey_name'   => 'required_if:survey_option,new|nullable|string|max:255',
            'pdf_path'          => 'nullable|file|mimes:pdf|max:10240',
            'puf_file'          => 'nullable|file|mimes:zip,rar|max:102400',
        ]);

        try {
            DB::beginTransaction();

            $postItem = PostItem::findOrFail($id);

            // Handle survey category
            if ($validated['survey_option'] === 'new') {
                $survey = PostItemsCat::create([
                    'cat_name'     => 'PUF',
                    'sub_category' => $validated['new_survey_name'],
                    'value'        => $validated['post_year'],
                ]);
                $surveySubCategory = $validated['new_survey_name'];
            } else {
                $survey = PostItemsCat::findOrFail($validated['existing_survey']);
                $surveySubCategory = $survey->sub_category;
            }

            $data = [
                'post_title'        => $validated['post_title'],
                'post_description'  => $validated['post_description'] ?? '',
                'post_description2' => $validated['post_description2'] ?? '',
                'post_type'         => $validated['post_type'],
                'post_survey'       => $validated['post_survey'],
                'post_year'         => $validated['post_year'],
                'date_pub'          => $validated['date_pub'] ?? 'NA',
                'post_cat'          => 'PUF',
            ];

            // // Handle image upload
            // if ($request->hasFile('pic_file')) {
            //     if ($postItem->pic_file && $postItem->pic_file !== 'NA') {
            //         $this->legacyFileService->deleteFile($postItem->pic_file);
            //     }
            //     $imagePath = $this->legacyFileService->uploadImage(
            //         $request->file('pic_file'),
            //         'PUF'
            //     );
            //     if ($imagePath) $data['pic_file'] = $imagePath;
            // }

            // Handle PDF upload
            if ($request->hasFile('pdf_path')) {
                if ($postItem->pdf_path) {
                    $this->legacyFileService->deleteFile($postItem->pdf_path);
                }
                $pdfPath = $this->legacyFileService->uploadPdf(
                    $request->file('pdf_path'),
                    'PUF'
                );
                if ($pdfPath) $data['pdf_path'] = $pdfPath;
            }

            $postItem->update($data);

            // Handle ZIP/RAR upload
            if ($request->hasFile('puf_file')) {
                $pufPath = $this->legacyFileService->uploadPuf(
                    $request->file('puf_file'),
                    $validated['post_title'],
                    $validated['post_year'],
                    $surveySubCategory
                );

                if ($pufPath) {
                    DB::table('puf_csv')->updateOrInsert(
                        ['post_item_id' => $id],
                        [
                            'name'         => $validated['post_title'],
                            'year'         => $validated['post_year'],
                            'file_path'    => $pufPath,
                            'updated_at'   => now(),
                        ]
                    );
                }
            } else {
                // Update name/year even if no new file
                DB::table('puf_csv')->where('post_item_id', $id)->update([
                    'name'       => $validated['post_title'],
                    'year'       => $validated['post_year'],
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'PUF item updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PUF update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update PUF item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $postItem = PostItem::findOrFail($id);

            if ($postItem->pic_file && $postItem->pic_file !== 'NA') {
                $this->legacyFileService->deleteFile($postItem->pic_file);
            }
            if ($postItem->pdf_path) {
                $this->legacyFileService->deleteFile($postItem->pdf_path);
            }

            DB::table('puf_csv')->where('post_item_id', $id)->delete();
            $postItem->delete();

            DB::commit();
            return redirect()->back()->with('success', 'PUF item deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PUF deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete PUF item.');
        }
    }
}
