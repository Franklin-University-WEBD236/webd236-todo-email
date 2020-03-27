<?php

class Logger {
  private static $instance;
  private $filename;

  const DEBUG = 0;
  const INFO = 1;
  const WARN = 2;
  const ERROR = 3;

  private function __construct($filename) {
    $this->filename = $filename;
  }
