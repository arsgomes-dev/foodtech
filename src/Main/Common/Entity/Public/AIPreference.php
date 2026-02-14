<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;

class AIPreference extends ModelClass {

    protected $table_db = "ai_preferences";
    private $table_db_primaryKey = "id";
    // --- CAMPOS DO BANCO ---
    private $id;
    private string $gcid;
    private $customer_gcid;
    private $channel_gcid;
    private $title;
    private $style;
    private $tone;
    private $voice_rules;
    private $reference_channels;
    private $target_audience;
    private $niche;
    private $video_goal;
    private $unique_value;
    private $brand_guidelines;
    private $video_length;
    private $video_style;
    private $editing_style;
    private $hook_type;
    private $cta_type;
    private $analysis_type;
    private $seo_focus;
    private $retention_focus;
    private $structure_rules;
    private $forbidden_words;
    private $priority_points;
    private $temperature;
    private $max_length;
    private $language_level;
    private $additional_instructions;
    private $model;

    // --- GETTERS / SETTERS ---

    public function getId() {
        return $this->id ?? null;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGcid() {
        if (isset($this->gcid)) {
            return $this->gcid;
        } else {
            return null;
        }
    }

    public function setGcid($gcid = null) {
        ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();
    }

    public function getCustomer_gcid() {
        return $this->customer_gcid ?? null;
    }

    public function setCustomer_gcid($customer_gcid) {
        $this->customer_gcid = $customer_gcid;
    }

    public function getChannel_gcid() {
        return $this->channel_gcid ?? null;
    }

    public function setChannel_gcid($channel_gcid) {
        $this->channel_gcid = $channel_gcid;
    }

    public function getStyle() {
        return $this->style ?? null;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

    public function getTitle() {
        return $this->title ?? null;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTone() {
        return $this->tone ?? null;
    }

    public function setTone($tone) {
        $this->tone = $tone;
    }

    public function getVoice_rules() {
        return $this->voice_rules ?? null;
    }

    public function setVoice_rules($voice_rules) {
        $this->voice_rules = $voice_rules;
    }

    public function getReference_channels() {
        return $this->reference_channels ?? null;
    }

    public function setReference_channels($reference_channels) {
        $this->reference_channels = $reference_channels;
    }

    public function getTarget_audience() {
        return $this->target_audience ?? null;
    }

    public function setTarget_audience($target_audience) {
        $this->target_audience = $target_audience;
    }

    public function getNiche() {
        return $this->niche ?? null;
    }

    public function setNiche($niche) {
        $this->niche = $niche;
    }

    public function getVideo_goal() {
        return $this->video_goal ?? null;
    }

    public function setVideo_goal($video_goal) {
        $this->video_goal = $video_goal;
    }

    public function getUnique_value() {
        return $this->unique_value ?? null;
    }

    public function setUnique_value($unique_value) {
        $this->unique_value = $unique_value;
    }

    public function getBrand_guidelines() {
        return $this->brand_guidelines ?? null;
    }

    public function setBrand_guidelines($brand_guidelines) {
        $this->brand_guidelines = $brand_guidelines;
    }

    public function getVideo_length() {
        return $this->video_length ?? null;
    }

    public function setVideo_length($video_length) {
        $this->video_length = intval($video_length);
    }

    public function getVideo_style() {
        return $this->video_style ?? null;
    }

    public function setVideo_style($video_style) {
        $this->video_style = $video_style;
    }

    public function getEditing_style() {
        return $this->editing_style ?? null;
    }

    public function setEditing_style($editing_style) {
        $this->editing_style = $editing_style;
    }

    public function getHook_type() {
        return $this->hook_type ?? null;
    }

    public function setHook_type($hook_type) {
        $this->hook_type = $hook_type;
    }

    public function getCta_type() {
        return $this->cta_type ?? null;
    }

    public function setCta_type($cta_type) {
        $this->cta_type = $cta_type;
    }

    public function getAnalysis_type() {
        return $this->analysis_type ?? null;
    }

    public function setAnalysis_type($analysis_type) {
        $this->analysis_type = $analysis_type;
    }

    public function getSeo_focus() {
        return $this->seo_focus ?? null;
    }

    public function setSeo_focus($seo_focus) {
        $this->seo_focus = $seo_focus;
    }

    public function getRetention_focus() {
        return $this->retention_focus ?? null;
    }

    public function setRetention_focus($retention_focus) {
        $this->retention_focus = $retention_focus;
    }

    public function getStructure_rules() {
        return $this->structure_rules ?? null;
    }

    public function setStructure_rules($structure_rules) {
        $this->structure_rules = $structure_rules;
    }

    public function getForbidden_words() {
        return $this->forbidden_words ?? null;
    }

    public function setForbidden_words($forbidden_words) {
        $this->forbidden_words = $forbidden_words;
    }

    public function getPriority_points() {
        return $this->priority_points ?? null;
    }

    public function setPriority_points($priority_points) {
        $this->priority_points = $priority_points;
    }

    public function getTemperature() {
        return $this->temperature ?? null;
    }

    public function setTemperature($temperature) {
        $this->temperature = floatval($temperature);
    }

    public function getMax_length() {
        return $this->max_length ?? null;
    }

    public function setMax_length($max_length) {
        $this->max_length = intval($max_length);
    }

    public function getLanguage_level() {
        return $this->language_level ?? null;
    }

    public function setLanguage_level($language_level) {
        $this->language_level = $language_level;
    }

    public function getAdditional_instructions() {
        return $this->additional_instructions ?? null;
    }

    public function setAdditional_instructions($additional_instructions) {
        $this->additional_instructions = $additional_instructions;
    }

    public function getModel() {
        return $this->model ?? null;
    }

    public function setModel($model) {
        $this->model = $model;
    }
}
