<?php

namespace ProductLabels\Document;

/**
* TextDocument
*/
class TextDocument extends Document
{
	protected function renderText($dimension, $product)
	{
		$maxLines = $this->maxLines($dimension, $product);
		$text = $this->trimText($maxLines, $product->textToPrint(), $dimension->width);
		$this->pdf->MultiCell($dimension->width, $dimension->height, $text);
	}

	protected function trimText($maxLines, $tekst, $lineWidth)
	{
		$line = 1;
		$words = $this->toWords($tekst);
        $lineString = '';
        $lastWordIndex = 0;
        for ($i = 0; $i < count($words); $i++) {
            if ($line <= $maxLines) {
                $curString = $lineString;
                if($words[$i] === ''){
                    //this value represents a new line
                    $line++;
                    $lineString = '';
                    $lastWordIndex = $i;
                    $words[$i] = "\n";
                }
                else{
                    if ($lineString != '')
                        $lineString .= ' ' . $words[$i]; //only add space if its not the first word on the line
                    else
                        $lineString .= $words[$i];
                    if ($this->pdf->GetStringWidth($lineString) + ($this->cellPadding()) > $lineWidth) {
                        $line++;
                        $lineString = $words[$i];
                        if ($line < $maxLines)
                            $lastWordIndex = $i;
                    }
                    else
                        $lastWordIndex = $i;
                }
            }
        }
        if ($lastWordIndex + 1 < count($words))
            $words = implode(' ', array_slice($words, 0, $lastWordIndex)) . '...'; // also pop last correct word to avoid a new line caused by ...
        else
            $words = implode(' ', $words);
        $tekst = $this->toText($words);
        return $tekst;
	}

}