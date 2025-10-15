### Exceptions
> tạo 1 Exceptions 
```php
namespace App\Exceptions;
use Exception;

class ValidationException extends Exception{
    protected $errors = [];
    public function __construct(array $errors){
        parent::__construct("Validation failed");
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
    
}
// debug Exception
try{

} catch(\Exception $e){
    echo '<pre>';
            echo "Exception: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . "\n";
            echo "Line: " . $e->getLine() . "\n";
            echo "Trace:\n" . $e->getTraceAsString();
            echo '</pre>';
            exit; // dừng lại để xem lỗi
}
```