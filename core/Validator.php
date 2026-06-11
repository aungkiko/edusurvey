<?php
/**
 * ===================================================
 * Input Validator
 * ===================================================
 * ตรวจสอบความถูกต้องของข้อมูล input
 */
class Validator
{
    private array $errors = [];
    private array $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * ตรวจสอบว่าต้องกรอก
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "กรุณากรอก{$label}";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบว่าเป็นตัวเลข
     */
    public function integer(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "{$label}ต้องเป็นตัวเลข";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบค่าต่ำสุด
     */
    public function min(string $field, int $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && is_numeric($this->data[$field]) && $this->data[$field] < $min) {
            $this->errors[$field] = "{$label}ต้องมีค่าอย่างน้อย {$min}";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบค่าสูงสุด
     */
    public function max(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && is_numeric($this->data[$field]) && $this->data[$field] > $max) {
            $this->errors[$field] = "{$label}ต้องมีค่าไม่เกิน {$max}";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบความยาวข้อความ
     */
    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "{$label}ต้องไม่เกิน {$max} ตัวอักษร";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบ email
     */
    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label}ไม่ถูกต้อง";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบว่าอยู่ในรายการที่กำหนด
     */
    public function in(string $field, array $values, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '' && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = "{$label}ไม่ถูกต้อง";
        }
        return $this;
    }
    
    /**
     * ตรวจสอบวันที่
     */
    public function date(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && $this->data[$field] !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "{$label}ไม่ใช่วันที่ที่ถูกต้อง";
            }
        }
        return $this;
    }
    
    /**
     * ตรวจสอบว่ามี error หรือไม่
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }
    
    /**
     * ตรวจสอบว่าผ่านทั้งหมดหรือไม่
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }
    
    /**
     * ดึงรายการ errors ทั้งหมด
     */
    public function errors(): array
    {
        return $this->errors;
    }
    
    /**
     * ดึง error ของ field เดียว
     */
    public function error(string $field): string
    {
        return $this->errors[$field] ?? '';
    }
    
    /**
     * Sanitize ข้อความ
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Sanitize ตัวเลข
     */
    public static function sanitizeInt($input): int
    {
        return (int)filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Sanitize ทศนิยม
     */
    public static function sanitizeFloat($input): float
    {
        return (float)filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
