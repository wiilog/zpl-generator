# ZPL Generator
Wrapper classes to generate ZPL, inspired from [teddy-dubal/weez-zpl](https://github.com/teddy-dubal/weez-zpl)

## Example
```php
use ZplGenerator\Label;
use ZplGenerator\Client\SocketClient;
use ZplGenerator\Printer\Printer;
use ZplGenerator\Elements\Codes\QrCode;
use ZplGenerator\Elements\Text\Text;
use ZplGenerator\Elements\Common\Align;

$logo = Text::create(0, 5)
    ->setWidth(102.6)
    ->setText("Wiilog")
    ->setAlignment(Align::CENTER)
    ->setMaxLines(1);

$qr = QrCode::create(0, 15)
    ->setContent("https://wiilog.fr/")
    ->setSize(10)
    ->setAlignment(Align::CENTER)
    ->setErrorCorrection(QrCode::EC_HIGHEST);

$client = SocketClient::create("127.0.0.1", 9100, 10);
$printer = Printer::create()
    ->setDimension(102.6, 102.6)
    ->setDPI(Printer::DPI_203);

$label = $printer->createLabel()
    ->with($qr)
    ->with($logo);

$printer->print($client, $label);
```

## Testing ZPL
Generated ZPL can be tester with http://labelary.com/viewer.html
