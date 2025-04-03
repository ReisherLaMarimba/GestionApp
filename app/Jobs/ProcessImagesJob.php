<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Laravel\Facades\Image;


class ProcessImagesJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $imagePath;
    protected $itemId;
    protected $column;

    /**
     * Job Constructor.
     *
     * @param string $imagePath - Path of the image
     * @param int $itemId - The ID of the item in the database
     * @param string $column - The column to update in the database
     */
    public function __construct($imagePath, $itemId, $column)
    {
        $this->imagePath = $imagePath;
        $this->itemId = $itemId;
        $this->column = $column;
    }

    /**
     * Main Job Handler.
     */
    public function handle()
    {
        try {
            Log::info("Processing image for item ID: {$this->itemId}");
            Log::info("IMAGE PATH: {$this->imagePath}");
            Log::info("COLUMN: {$this->column}");

            if(!Storage::get($this->imagePath)){
                Log::error("Image file does not exist: {$this->imagePath}");
                return;
            }

            // Retrieve image from storage
            $imageData = Storage::get($this->imagePath);


            // Use Intervention Image to process the image
            $image = Image::read($imageData)
                ->resize(800, 600) // Resize the image
                ->resizeCanvas(1280, 720); //No me acuerdo

            // Encode the image to JPEG - Compressed with 70% quality
            Log::error("BEFORE encode");
            $encodedImage = $image->toJpeg(quality: 70, progressive: false, strip: true);
            Log::error("AFTER encode");

            // Define the new storage path
            $filename = basename($this->imagePath);
            $destinationPath = "items/{$this->column}/";
            $fullPath = "{$destinationPath}{$filename}";

            // Make sure the directory exists
            Storage::makeDirectory($destinationPath);

            // Save the processed image
            Storage::put($fullPath, $encodedImage);

            // Update the database with the processed image path
            $item = Item::find($this->itemId);
            if ($item) {
                $existingImages = json_decode($item->{$this->column}, true) ?? [];
                $existingImages[] = $fullPath;
                $item->update([$this->column => json_encode($existingImages)]);
            }

            // Optionally delete the original image
            Storage::delete($this->imagePath);

//            Log::info("Image processing completed successfully: {$fullPath}");
        } catch (\Exception $e) {
            // Log any errors
            Log::error("Error processing image: {$e->getMessage()}");
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
