<?php
namespace App\Repositories;

use App\Models\Attributes;
use App\Models\AttributeValue;
use Attribute;
use Illuminate\Support\Facades\DB;

Class AttributeRepository extends BaseRepository {

    public function __construct(Attributes $model)
    {
        parent::__construct($model);
    }

    public function getAllAttributesValue(string $id){
        return Attributes::where('id', $id)->first();
    }

    public function createAttributes(array $data){
        $attribute = $this->create($data);

        if(isset($data['details']) && !empty($data['details'])){

            foreach($data['details'] as $value){
                $attribute->attributeValues()->create([
                    'attribute_id' =>  $attribute->id,
                    'value' => $value
                ]);
            }
        }

        return $attribute;
    }

   public function updateAttributes($id, array $data)
{
    $attribute = $this->update($id, $data);

    if (isset($data['details']) && !empty($data['details'])) {
        foreach ($data['details'] as $value) {
        
            $existingValue = $attribute->attributeValues()->where('value', $value)->first();

          
            if (!$existingValue) {
                $attribute->attributeValues()->create([
                    'attribute_id' => $attribute->id,
                    'value' => $value
                ]);
            }
        }
    }

    return $attribute;
}

    public function getAttributeValuesWhereIn($attribute){
        return Attributes::whereHas('attributeValues', function($query) use ($attribute) {
            $query->where('attribute_id', $attribute);
        })->with('attributeValues')->get();
    }

    public function getAllAtrributeWithValue(){
        return Attributes::with('attributeValues')->get();
    }

    public function getSizeAttributes()
    {
       return AttributeValue::where('attribute_id', 1)->get();
    }

    public function getColorAttributes()
    {
        return AttributeValue::where('attribute_id', 2)->get();
    }

}


?>
