<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* core/themes/claro/templates/media-library/views-view--media-library.html.twig */
class __TwigTemplate_5f0fccad5ef99fdefa94439295fda7f8 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 36
        $context["classes"] = ["view", ("view-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 38
($context["id"] ?? null), 38, $this->source))), ("view-id-" . $this->sandbox->ensureToStringAllowed(        // line 39
($context["id"] ?? null), 39, $this->source)), ("view-display-id-" . $this->sandbox->ensureToStringAllowed(        // line 40
($context["display_id"] ?? null), 40, $this->source)), ((        // line 41
($context["dom_id"] ?? null)) ? (("js-view-dom-id-" . $this->sandbox->ensureToStringAllowed(($context["dom_id"] ?? null), 41, $this->source))) : (""))];
        // line 44
        yield "<div";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 44), 44, $this->source), "html", null, true);
        yield ">
  ";
        // line 45
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 45, $this->source), "html", null, true);
        yield "
  ";
        // line 46
        if (($context["title"] ?? null)) {
            // line 47
            yield "    ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 47, $this->source), "html", null, true);
            yield "
  ";
        }
        // line 49
        yield "  ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 49, $this->source), "html", null, true);
        yield "
  ";
        // line 50
        if (($context["exposed"] ?? null)) {
            // line 51
            yield "    <div class=\"view-filters\">
      ";
            // line 52
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["exposed"] ?? null), 52, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 55
        yield "  ";
        if (($context["header"] ?? null)) {
            // line 56
            yield "    <div class=\"view-header\">
      ";
            // line 57
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header"] ?? null), 57, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 60
        yield "  ";
        if (($context["attachment_before"] ?? null)) {
            // line 61
            yield "    <div class=\"attachment attachment-before\">
      ";
            // line 62
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_before"] ?? null), 62, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 65
        yield "
  ";
        // line 66
        if (($context["rows"] ?? null)) {
            // line 67
            yield "    <div class=\"view-content\">
      ";
            // line 68
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["rows"] ?? null), 68, $this->source), "html", null, true);
            yield "
    </div>
  ";
        } elseif (        // line 70
($context["empty"] ?? null)) {
            // line 71
            yield "    <div class=\"view-empty\">
      ";
            // line 72
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty"] ?? null), 72, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 75
        yield "
  ";
        // line 76
        if (($context["pager"] ?? null)) {
            // line 77
            yield "    ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["pager"] ?? null), 77, $this->source), "html", null, true);
            yield "
  ";
        }
        // line 79
        yield "  ";
        if (($context["attachment_after"] ?? null)) {
            // line 80
            yield "    <div class=\"attachment attachment-after\">
      ";
            // line 81
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_after"] ?? null), 81, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 84
        yield "  ";
        if (($context["more"] ?? null)) {
            // line 85
            yield "    ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["more"] ?? null), 85, $this->source), "html", null, true);
            yield "
  ";
        }
        // line 87
        yield "  ";
        if (($context["footer"] ?? null)) {
            // line 88
            yield "    <div class=\"view-footer\">
      ";
            // line 89
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer"] ?? null), 89, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 92
        yield "  ";
        if (($context["feed_icons"] ?? null)) {
            // line 93
            yield "    <div class=\"feed-icons\">
      ";
            // line 94
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["feed_icons"] ?? null), 94, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 97
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["id", "display_id", "dom_id", "attributes", "title_prefix", "title", "title_suffix", "exposed", "header", "attachment_before", "rows", "empty", "pager", "attachment_after", "more", "footer", "feed_icons"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/claro/templates/media-library/views-view--media-library.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  187 => 97,  181 => 94,  178 => 93,  175 => 92,  169 => 89,  166 => 88,  163 => 87,  157 => 85,  154 => 84,  148 => 81,  145 => 80,  142 => 79,  136 => 77,  134 => 76,  131 => 75,  125 => 72,  122 => 71,  120 => 70,  115 => 68,  112 => 67,  110 => 66,  107 => 65,  101 => 62,  98 => 61,  95 => 60,  89 => 57,  86 => 56,  83 => 55,  77 => 52,  74 => 51,  72 => 50,  67 => 49,  61 => 47,  59 => 46,  55 => 45,  50 => 44,  48 => 41,  47 => 40,  46 => 39,  45 => 38,  44 => 36,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/claro/templates/media-library/views-view--media-library.html.twig", "/var/www/html/web/core/themes/claro/templates/media-library/views-view--media-library.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 36, "if" => 46);
        static $filters = array("clean_class" => 38, "escape" => 44);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['clean_class', 'escape'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
