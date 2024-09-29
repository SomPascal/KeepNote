<?php

namespace App\Libraries;

class FormElement extends \stdClass implements \Stringable
{
    protected string $element;

    public function __construct (string $element, array|object $attrs)
    {
        $this->element = $element;

        foreach ($attrs as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    public function __toString(): string
    {
        return $this->element;
    }
}

class Form 
{

    /**
     * @var ?string The form action
     */
    protected string $action = "";

    /**
     * If set to true, the form should implement the remember_me feature
     */
    public bool $remember_me = false;

    /**
     * The form description
     */
    public string $description = "";

    /**
     * @var object the form attr
     */
    public object $attrs;

    /**
     * @var string the extra html of the form close according to the form_close() function...
     */
    protected string $close_extra = "";

    /**
     * @var array the form hidden inputs
     */
    protected array $hidden = [];

    /**
     * @var array The form tags saves
     */
    protected array $saves = [];

    public function __construct(array $attrs, string $close_extra="")
    {
        $this->close_extra = $close_extra;
        $this->attrs = (object) $attrs;
    }

    public static function create(array $attrs, string $close_extra=""): self
    {
        if ($attrs == []) throw new \ValueError("form attributes must not be empty");
        return new static($attrs, $close_extra);
    }

    public static function createFromConfig(object $config): self
    {
        $right_config = [];

        $right_config["id"] = $config->id ?? ' ';

        if (isset($config->method)) 
            $right_config["method"] = $config->method;

        $right_config["autocomplete"] = ($config->autocomplete == true) ? "on" : "off";

        if (isset($config->action)) 
            $right_config["action"] = $config->action;
        
        if (isset($config->sendFile) && $config->sendFile == true) 
            $right_config["enctype"] = "multipart/form-data";

        $form = self::create(array_merge($right_config, $config->formAttrs));
        $form->description = $config->description;

        if (! empty($config->formInputs)) 
        {
            foreach ($config->formInputs as $key => $input) 
            {
                $form->input(
                    key: $key, 
                    attrs: $input ?? [],
                    add_attrs: ["icon" => $config->formInputIcons[$key] ?? "icon"]
                );
            }

            foreach ($config->formLabels as $key => $value) 
            {
                $form->label(
                    key: $key,
                    attrs: $value ?? []
                );
            }
        }
        $form->remember_me = $config->handleRememberMe;
        
        $form->button("submit", $config->submitButton ?? []);
        $form->button("another", $config->anotherButton ?? []);

        return $form;
    }

    public function open(): string
    {
        $opening = "<form ";
        
        foreach ($this->attrs as $attr => $value)
        {
            $opening .= sprintf("%s=\"%s\" ", esc($attr, "attr"), esc($value, "attr"));
        }
        $opening .= " >";
        
        return $opening;
    }

    public function close(): string
    {
        return form_close($this->close_extra);
    }

    public function input(string $key, array $attrs=[], array $label_attrs=[], array|object $add_attrs = [])
    {
        if ($attrs == []) return $this->saves["input"][$key];

        $type = $attrs["type"] ?? "";

        if ($type == "password") 
        {
            $input = form_password(value: $attrs["value"] ?? "", extra: $attrs);
        }
        else $input = form_input(value: $attrs["value"] ?? "", extra: $attrs);

        if ($label_attrs == [])
        {
            $this->saves["input"][$key] = new FormElement($input, (object) array_merge($add_attrs, $attrs));
            return;
        }

        $text = $label_attrs["text"] ?? "";
        unset($label_attrs["text"]);

        $label = form_label("$text\n\t$input", attributes: $label_attrs);
        $this->saves["input"][$key] = new FormElement($label, (object) array_merge($add_attrs, $attrs));
    }

    public function label(string $key, array $attrs=[], array|object $add_attrs = [])
    {
        if ($attrs == []) return $this->saves["label"][$key];

        $text = $attrs["text"] ?? "";
        unset($attrs["text"]);

        $label = form_label($text, attributes: $attrs);
        $this->saves["label"][$key] = new FormElement($label, (object) array_merge($add_attrs, $attrs));
    }

    /**
     * @return FormElement[]
     */
    public function inputs(): array
    {
        return $this->saves["input"] ?? [];
    }

    public function radio(string $key, array $attrs=[], array $label_attrs=[], array|object $add_attrs = [])
    {
        if ($attrs == []) return $this->saves["radio"][$key];

        $value = $attrs["value"] ?? "";
        unset($attrs["value"]);

        $radio = form_radio(value: $value, extra: $attrs);
        if ($label_attrs == [])
        {
            $this->saves["radio"][$key] = new FormElement($radio, (object) array_merge($add_attrs, $attrs));
            return;
        }
        $text = $label_attrs["text"] ?? "";
        unset($label_attrs["text"]);

        $label = form_label("$text\n\t$radio", attributes: $label_attrs);
        $this->saves["radio"][$key] = new FormElement($label, (object) array_merge($add_attrs, $attrs));
    }

    public function radios(): array
    {
        return $this->saves["radio"] ?? [];
    }

    public function submit(string $key, array $attrs=[], array|object $add_attrs = [])
    {
        if ($attrs == []) return $this->saves["submit"][$key];

        $text = $attrs["text"] ?? "";
        unset($attrs["text"]);

        $submit = form_submit(value: $text, extra: $attrs);
        $this->saves["submit"][$key] = new FormElement($submit, (object) array_merge($add_attrs, $attrs));
    }

    public function submits(): array
    {
        return $this->saves["submit"] ?? [];
    }

    public function button(string $key, array $attrs=[], array $label_attrs = [], array|object $add_attrs = [])
    {
        if ($attrs == []) return $this->saves["button"][$key];

        $text = $attrs["text"] ?? "";
        unset($attrs["text"]);

        $button = form_button(content: $text, extra: $attrs);
        if ($label_attrs == []) 
        {
            $this->saves["button"][$key] = new FormElement($button, (object) array_merge($add_attrs, $attrs));
            return;
        }
        $text = $label_attrs["text"] ?? "";
        unset($label_attrs["text"]);

        $label = form_label("$text\n\t$button", attributes: $label_attrs);

    }

    public function buttons(): array
    {
        return $this->saves["button"] ?? [];
    }
}