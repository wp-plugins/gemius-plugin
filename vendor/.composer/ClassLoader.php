<?php
 namespace Composer\Autoload; class ClassLoader { private $prefixes = array(); private $fallbackDirs = array(); public function getPrefixes() { return $this->prefixes; } public function getFallbackDirs() { return $this->fallbackDirs; } public function add($prefix, $paths) { if (!$prefix) { $this->fallbackDirs = (array) $paths; return; } if (isset($this->prefixes[$prefix])) { $this->prefixes[$prefix] = array_merge( $this->prefixes[$prefix], (array) $paths ); } else { $this->prefixes[$prefix] = (array) $paths; } } public function register($prepend = false) { spl_autoload_register(array($this, 'loadClass'), true, $prepend); } public function unregister() { spl_autoload_unregister(array($this, 'loadClass')); } public function loadClass($class) { if ($file = $this->findFile($class)) { require $file; return true; } } public function findFile($class) { if ('\\' == $class[0]) { $class = substr($class, 1); } if (false !== $pos = strrpos($class, '\\')) { $classPath = DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0, $pos)); $className = substr($class, $pos + 1); } else { $classPath = null; $className = $class; } $classPath .= DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php'; foreach ($this->prefixes as $prefix => $dirs) { foreach ($dirs as $dir) { if (0 === strpos($class, $prefix)) { if (file_exists($dir . $classPath)) { return $dir . $classPath; } } } } foreach ($this->fallbackDirs as $dir) { if (file_exists($dir . $classPath)) { return $dir . $classPath; } } } } 