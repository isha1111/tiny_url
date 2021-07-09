<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinyUrl extends Model
{
    use HasFactory;

    /**
     * Constructs the class
     */
    public function __construct() {
    }

    /**
     * Get tinyUrl from longUrl
     */
    public function getTinyUrlFromLongUrl(string $longUrl, string $expiryDate = null): string
    {
        $tinyUrl = $this->urlExistsInDb($longUrl, $expiryDate);
        if(!filled($tinyUrl)){
            $id = (int)$this->insertUrlInDb($longUrl, $expiryDate);
            $tinyUrl = $this->getShortCodeFromId($id);
        }
        return $tinyUrl;
    }

    /**
     * Check if longUrl exists in database 
     * If record exists, returns matching tinyUrl 
     * if expiryDate is supplied, update record with provided expiryDate
     */
    public function urlExistsInDb(string $longUrl, string $expiryDate = null): string
    {
        $result = self::where('longUrl',$longUrl)->get()->last();
        $tinyUrl = '';

        if(filled($result)){
            // if expiryDate supplied update expiry
            if(filled($expiryDate)) {
                self::where('id', $result->id)->update(array('expiryDate' => $expiryDate));
                $tinyUrl = $result->tinyUrl;
            }
            else{
                // check expiry
                if($result->expiryDate) {
                    $recordExpiryDate = Carbon::createFromFormat('Y-m-d', $result->expiryDate);
                    if(!$recordExpiryDate->isPast()){
                        $tinyUrl = $result->tinyUrl;
                    }
                }
                else{
                    $tinyUrl = $result->tinyUrl;
                }
            }
        }

        return $tinyUrl;
    }

    /**
     * Returns the short code for a longUrl
     */
    public function getShortCodeFromId(string $id): string
    {
        $shortCode = base_convert($id, 10, 36);
        $this->insertShortCodeInDb($id, $shortCode);
        return $shortCode;
    }

    /**
     * Returns the urlVisit for a record
     */
    public function geturlVisitForATinyUrlFromDatabase(string $id): string
    {
        $record = self::where('id', $id)->get()->first();

        return $record->url_visit;
    }

    /**
     * Create record for longUrl in database
     */
    public function insertUrlInDb(string $longUrl, string $expiryDate = null): string
    {
        $this->longUrl = $longUrl;
        if(filled($expiryDate)) {
            $this->expiryDate = $expiryDate;
        }
        $this->save();  
        $insertedId = $this->id;
        return $insertedId;
    }

    /**
     * update the tinyUrl value in Database
     */
    public function insertShortCodeInDb(int $id, string $code): void
    {
        self::where('id', $id)->update(array('tinyUrl' => $code));
    }

    /**
     * Gets longUrl for a given tinyUrl
     */
    public function getLongUrlFromDb(string $tinyUrl): string
    {
        $longUrl = '';
        $result = self::where('tinyUrl', $tinyUrl)->get()->last();
        if (filled($result)) {
            $longUrl = $result->longUrl;
            // update visit count for url
            $this->setRedirectTinyUrlVisitCount((int)$result->id, (int)$result->url_visit);
        }
        return $longUrl;
    }

    /**
     * Updates the urlVisit count in database for a shorturl
     */
    public function setRedirectTinyUrlVisitCount(int $id, int $count): void
    {
        if(!filled($count)) {
            $udatedCount = 1; //first time visit to tinyUrl
        } 
        else {
            $udatedCount = $count + 1;
        }
        self::where('id', $id)->update(array('url_visit' => $udatedCount));
    }
}
