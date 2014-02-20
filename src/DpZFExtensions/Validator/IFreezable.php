<?php
/**
 * User: dpeuscher
 * Date: 20.02.14
 */
namespace DpZFExtensions\Validator;

interface IFreezable {
    public function isFrozen();
    public function freeze();
}