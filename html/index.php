<?php

use Swagger\Client\Models\Artist;
use Swagger\Client\Models\Purchase;
use Swagger\Client\Models\PurchaseItem;
use Swagger\Client\ObjectSerializer;

require '/app/vendor/autoload.php';

class HtmlGenerator {
    private array $data;

    function __construct($data) {
        $this->data = $data;
    }

    function renderForm($schema, $model1, $level) {
        return "<form class='schema' onsubmit='submitForm(\"$schema\", this, event)'>"
            . $this->renderInnerForm($schema, $model1, $level)
            . "<button type='submit'>Submit</button>"
            . "</form>";
    }

    function renderInnerForm($schema, $model1, $level) {
        $requiredFields = $model1['required'] ?? [];
        $fields = $model1['properties'];

        // TODO form per schema?

        $result = [];
        foreach ($fields as $name => $props) {
            $type = $props['type'] ?? 'string';

            if ($type == "array") {
                // TODO need to support a list of things?
                // Currently just shows add form for 1, but should support adding many?
                // May require JS to handle this, or reload the page after each add?
                $items = $props['items'];
                // This doesn't have to be a $ref, could be a nested thing?
                $ref = $items['$ref'] ?? null;
                if ($level < 3) {
                    $refSchema = explode("/", $ref);
                    $refSchema = $refSchema[count($refSchema) - 1];
                    $subSchema = $this->data['components']['schemas'][$refSchema];
                    $subForm = $this->renderNestedForm($name . "[0]", $subSchema, $level + 1);
                    // Nested schemas get smaller headers.
                    $title = "<h4>$name</h4>\n";
                    $result[] = "$title<div class='subSchema'>$subForm</div>";
                }
            } else {
                // Most fields are simple inputs.
                $result[] = $this->renderField($name, $props, $requiredFields);
            }
        }
        // Object header
        $title = '';
        if ($level === 0) {
            $title = "<h3>$schema</h3>\n";
        } else if ($level === 1) {
            // Nested schemas get smaller headers.
            $title = "<h4>$schema</h4>\n";
        }
        // TODO method should be based on new/existing?
        return "$title" . join("\n", $result);
    }

    function renderNestedForm($prefix, $model1, $level) {
        $requiredFields = $model1['required'] ?? [];
        $fields = $model1['properties'];

        $result = [];
        foreach ($fields as $n => $props) {
            $type = $props['type'] ?? 'string';
            $name = "$prefix" . "[$n]";

            if ($type == "array") {
                // TODO support double nested?
                throw new Exception("Double nesting not supported (YET)");
            } else {
                // Most fields are simple inputs.
                $result[] = $this->renderField($name, $props, $requiredFields);
            }
        }
        return join("\n", $result);
    }

    private function renderField($name, $props, $requiredFields) {
        $type = $props['type'] ?? 'string';
        $required = in_array($name, $requiredFields) ? 'required' : '';
        $labels = true;
        // TODO make a unique id for every property in the page (use hierarchy?).
        $id = $name . mt_rand();
        $result = '';
        if ($props['readOnly'] ?? false === true) {
            // TODO could display the value if we have one?
            return "<input hidden class='$name $type' name='$name' type='text' />";
        }
        switch ($type) {
            case "string":
                $result = "<input $required class='$name $type' id='$id' name='$name' type='text' />";
                break;
            case "integer":
                $result = "<input $required class='$name $type' id='$id' name='$name' type='number' />";
                break;
            case "number":
                $result = "<input $required class='$name $type' id='$id' name='$name' type='number' step='any' />";
                break;
            default:
                throw new RuntimeException("Field with unknown type: $type");
        }
        if ($labels) {
            $result = "<p>\n<label for='$id'>$name</label>$result\n</p>\n";
        }
        return $result;
    }
}

$data = yaml_parse_file("/app/testSpec.yml");

$route = $_SERVER['REQUEST_URI'];
// TODO route can't be trusted as its a user input.
$route = htmlspecialchars($route);

$generator = new HtmlGenerator($data);

$parts = explode("/", $route);
// $parts[0] should always be empty string because all routes begin with /
$intent = $parts[1];

$title = "";
$html = "";
if ($intent == "form") {
    $schema = $parts[2];
    $title = "$schema form";
    if (array_key_exists($schema, $data['components']['schemas'])) {
        $model1 = $data['components']['schemas'][$schema];
        try {
            $html = $generator->renderForm($schema, $model1, 0);
        } catch(Exception $e) {
            // Extra context show the model.
            error_log("Error rendering $schema " . json_encode($model1, JSON_PRETTY_PRINT));
            throw $e;
        }
    } else {
        $html = "Unknown schema $schema<br>\n";
    }
} else {
    $title = "Home";
    $html = "Route: $route<br>\n";
    // The default page shows a list of schemas.
    foreach ($data['components']['schemas'] as $schema => $model1) {
        $html .= "<li><a href='/form/$schema'>$schema</a></li>\n";
    }
}

$head = "<link rel='stylesheet' href='/static/css/test.css' />\n";
$head .= "<script src='/static/js/test.js'></script>\n";
$head .="<title>$title</title>";
echo("<html>\n");
echo("<head>\n$head</head>\n");
echo("<body>\n");
echo($html);
echo("</body>\n");
echo("</html>\n");
