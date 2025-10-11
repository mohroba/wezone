<?php

return [
    'name' => 'Ad',

    'advertisable_types' => [
        'definitions' => [
            Modules\Ad\Advertisable\Definitions\CarAdvertisableTypeDefinition::class,
            Modules\Ad\Advertisable\Definitions\RealEstateAdvertisableTypeDefinition::class,
            Modules\Ad\Advertisable\Definitions\JobAdvertisableTypeDefinition::class,
        ],
    ],
];
