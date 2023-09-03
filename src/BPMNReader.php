<?php

namespace BeyondCRUD\LaravelCamundaClient;

class BPMNReader
{
    private \SimpleXMLElement $xml;

    public function __construct(string $file)
    {
        /** @var string */
        $string = file_get_contents($file);
        $this->xml = new \SimpleXMLElement($string);
        $this->xml->registerXPathNamespace('bpmn', 'http://www.omg.org/spec/BPMN/20100524/MODEL');
        $this->xml->registerXPathNamespace('camunda', 'http://camunda.org/schema/1.0/bpmn');
    }

    public function getForms(): array
    {
        /** @var array */
        $array1 = $this->xml->xpath('//bpmn:startEvent');
        /** @var array */
        $array2 = $this->xml->xpath('//bpmn:userTask');
        $nodes = $array1 + $array2;

        $forms = [];
        foreach ($nodes as $node) {
            try {
                $fields = $node->xpath('bpmn:extensionElements/camunda:formData/camunda:formField');
                $formFields = [];
                foreach ($fields as $field) {
                    /** @var array */
                    $array = $field->xpath('camunda:properties/camunda:property');
                    $properties = collect($array)
                        ->transform(fn ($node) => [
                            (string) $node->attributes()->id => (string) $node->attributes()->value
                        ])
                        ->toArray();

                    $formFields[] = [
                        'id' => (string) $field->attributes()->id,
                        'label' => (string) $field->attributes()->label,
                        'type' => (string) $field->attributes()->type,
                        'properties' => $properties,
                    ];
                }
                $form = [
                    'id' => (string) $node->attributes()->id,
                    'label' => (string) $node->attributes()->name,
                    'fields' => $formFields,
                ];
                $forms[] = $form;
            } catch (\ErrorException) {
            }
        }

        return $forms;
    }
}
