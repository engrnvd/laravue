<?php


namespace App\Helpers;


use App\Customer;

class DbHelper
{
    public static function getNextId($sequence)
    {
        $document = \DB::getCollection('id_counters')->findOneAndUpdate(
            ['_id' => $sequence],
            ['$inc' => ['sequence' => 1]],
            ['new' => true, 'upsert' => true]
        );
        return $document->sequence;
    }

    public static function switchDatabase($db, $currentConnection = 'mongodb')
    {
        if (\DB::getDriverName() === 'mongodb') {
            \DB::selectDatabase($db);
        } else {
            app('db')->disconnect($currentConnection);
            \Config::set("database.connections.{$currentConnection}.database", $db);
        }
    }

    public static function prepNameForDb($column)
    {
        $column = strtolower(preg_replace('/[^a-zA-Z\d]/', '_', trim($column)));
        // sometimes, the first column in a csv contains special characters that convert to ___
        $column = str_replace('___', '', $column);
        return $column;
    }

    public static function renameCollection($collection, $newName, $db = '')
    {
        if (!$db) $db = config('database.connections.mongodb.database');

        \DB::connection('mongodb')->getMongoClient()->admin->command([
            'renameCollection' => "{$db}.{$collection}",
            'to' => "{$db}.{$newName}",
        ]);
    }
}
