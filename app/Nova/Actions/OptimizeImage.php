<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OptimizeImage extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {

            $imageFile = pathinfo($model->thumbnail);

            if (array_key_exists('extension', $imageFile)) {
                $originalImage = $imageFile['dirname'] . '/' . $imageFile['filename'] . '.' . $imageFile['extension'];
                $optimizedImage = $imageFile['dirname'] . '/' . $imageFile['filename'] . '_resized.' . $imageFile['extension'];
                $optimizedImage = str_replace($imageFile['extension'], 'jpg', $optimizedImage); // always jpg
            } else {
                $originalImage = $imageFile['dirname'] . '/' . $imageFile['filename'];
                $optimizedImage = $imageFile['dirname'] . '/' . $imageFile['filename'] . '_resized';
                // $optimizedImage = str_replace($imageFile['extension'], 'jpg', $optimizedImage); // always jpg
            }


            try {

                if (app()->environment() === 'local') {
                    $photo = Image::make(Storage::disk('public')->get($originalImage))->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    Storage::put($optimizedImage, $photo->stream('jpg', 75));

                }
                else {
                    $photo = Image::make(Storage::get($originalImage))->resize(1920, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    Storage::put($optimizedImage, $photo->stream('jpg', 75));
                }
            }
            catch (\Exception $e) {

            }

            $model->thumbnail = $optimizedImage;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
