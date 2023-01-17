<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\Models\Traits;

    use Spatie\Image\Manipulations;
    use Spatie\MediaLibrary\InteractsWithMedia;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    /**
     * @property string $overview_title
     * @property string $overview_description
     */
    class HasOverviewAttributes {
        use InteractsWithMedia;

        public function initialiseHasOverviewAttributes(): void
        {
            $this->registerOverviewImageMediaCollectionAndConversion();
        }

        public function getOverviewTitle(): string {
            if(!$this->overview_title && isset($this->title)){
                return $this->title;
            }
            if(!$this->overview_title && isset($this->seo_title)){
                return $this->seo_title;
            }

            return $this->overview_title;
        }

        public function getOverviewDescription(): string {
            if(!$this->overview_description && isset($this->seo_description)){
                return $this->seo_description;
            }

            return $this->overview_description;
        }

        protected function registerOverviewImageMediaCollectionAndConversion() {
            $this->addMediaCollection($this->getOverviewImageCollection())
                ->registerMediaConversions(function(Media $media){
                    $this->addMediaConversion($this->getOverviewImageConversionName())
                        ->fit(Manipulations::FIT_CROP, 600, 600)
                        ->nonQueued();
                });
        }

        public function addOverviewImage(string $imagePath): void {
            $this->addMedia($imagePath)
                ->toMediaCollection($this->getOverviewImageCollection());
        }

        public function getOverviewImageConversionName(): string {
            return 'overview_image';
        }

        public function getOverviewImageCollection(): string {
            return 'overview_image';
        }

        public function getOverviewImageUrl(string $conversion=null): string {
            return $this->getFirstMediaUrl($this->getOverviewImageCollection(), $conversion ?? $this->getOverviewImageConversionName());
        }
    }
