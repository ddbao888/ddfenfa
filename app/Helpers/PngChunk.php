<?php


namespace App\Helpers;


class PngChunk
{
    public $png;
    public $idxStart;
    public $type;
    public $dataLength;
    public $crc;
    public function __construct($png,$idx) {
        $this->png = $png;
        $this->idxStart = $idx;
        $res = fseek($this->png->handle,$idx,SEEK_SET);
        if ($res == -1) {
            throw new UnexpectedValueException();
        }
        $val = fread($this->png->handle,CHUNK_SIZE_LENGHT);
        if ($val == FALSE) {
            throw new UnexpectedValueException();
        }
        $val = unpack('N',$val);
        $this->dataLength = $val[1];
        $val = fread($this->png->handle,CHUNK_TYPE_LENGHT);
        if ($val == FALSE) {
            throw new UnexpectedValueException();
        }
        $this->type = $val;
        $res = fseek($this->png->handle,$this->dataLength,SEEK_CUR);
        if ($res == -1) {
            throw new UnexpectedValueException();
        }
        $val = fread($this->png->handle,CHUNK_CRC_LENGHT);
        if ($val == FALSE) {
            throw new UnexpectedValueException();
        }
        $this->crc = $val;
        if (strcmp($this->type,CGBI_CHUNK_TYPE) == 0) {
            $this->png->isIphone = TRUE;
        }
        if (strcmp($this->type,IHDR_CHUNK_TYPE) == 0) {
            $res = fseek($this->png->handle,$this->idxStart +CHUNK_SIZE_LENGHT +CHUNK_TYPE_LENGHT,SEEK_SET);
            if ($res == -1) {
                throw new UnexpectedValueException();
            }
            $val = fread($this->png->handle,$this->dataLength);
            if ($val == FALSE) {
                throw new UnexpectedValueException();
            }
            $val = unpack('Nwidth/Nheight/Cdepth/Ccolor/ccompression/cfilter/Cinterlace',$val);
            $this->png->width = $val['width'];
            $this->png->height = $val['height'];
            $this->png->compression = $val['compression'];
        }
    }
    function getNextChunkIdx() {
        return $this->idxStart +CHUNK_SIZE_LENGHT +CHUNK_TYPE_LENGHT +$this->dataLength +CHUNK_CRC_LENGHT;
    }
}