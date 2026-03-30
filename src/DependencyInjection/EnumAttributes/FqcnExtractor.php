<?php

declare(strict_types=1);

namespace JaWitold\EnumAttributeLookupBundle\DependencyInjection\EnumAttributes;

class FqcnExtractor
{
    public function extract(string $filePath): string
    {
        if (false === $content = file_get_contents($filePath)) {
            return '';
        }

        $namespace = '';

        for ($i = 0; $i < count($tokens = token_get_all($content)); ++$i) {
            if (!is_array($token = $tokens[$i])) {
                continue;
            }

            switch ($token[0]) {
                case T_NAMESPACE:
                    while (true) {
                        if (!array_key_exists(++$i, $tokens)
                            || !is_array($nextToken = $tokens[$i])) {
                            continue 3;
                        }

                        if (in_array($nextToken[0], [T_NAME_QUALIFIED, T_STRING, T_NAME_FULLY_QUALIFIED], true)) {
                            $namespace .= $nextToken[1];
                        }
                    }

                    // no break
                case T_ENUM:
                    if (!array_key_exists($i += 2, $tokens)
                        || !is_array($nextToken = $tokens[$i])) {
                        continue 2;
                    }

                    if (T_STRING === $nextToken[0]) {
                        return $namespace.'\\'.$nextToken[1];
                    }

                    break;
            }
        }

        return '';
    }
}
