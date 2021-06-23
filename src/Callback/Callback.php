<?php

namespace Fiks\YooKassa\Callback;

use Closure;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use SuperClosure\Serializer;

class Callback
{
    /**
     * Read Callback Function From File
     *
     * @return bool|string
     */
    public function exec(string $type = 'success')
    {
        $serializer = new Serializer();
        $path = "yookassa/{$type}.callable";
        try {
            return Storage::disk('public')->read($path);
        } catch(FileNotFoundException $exception) {
            return false;
        }
    }

    /**
     * Save Success Function Callback
     *
     * @param Closure $next
     *
     * @return bool
     */
    public function success(Closure $next)
    {
        # Create Serializer
        $serializer = new Serializer();
        # Path to Save Callback Function
        $path = "yookassa/success.callable";
        # Save Callback Function
        Storage::disk('public')->put($path, $serializer->serialize($next));

        return true;
    }

    /**
     * Save Failed Function Callback
     *
     * @param Closure $next
     *
     * @return bool
     */
    public function failed(Closure $next)
    {
        # Create Serializer
        $serializer = new Serializer();
        # Path to Save Callback Function
        $path = "yookassa/failed.callable";
        # Save Callback Function
        Storage::disk('public')->put($path, $serializer->serialize($next));

        return true;
    }
}