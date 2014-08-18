<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Symfony\CS\Fixer;

use Symfony\CS\FixerInterface;
use Symfony\CS\Token;
use Symfony\CS\Tokens;

/**
 * @author Lyndon Wong
 */
class SpacesTwoSidesFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $operator = array(T_AND_EQUAL , T_BOOLEAN_AND , T_BOOLEAN_OR , T_CONCAT_EQUAL , T_DIV_EQUAL , T_DEC , T_DOUBLE_ARROW , T_INC , T_IS_EQUAL , T_IS_GREATER_OR_EQUAL , T_IS_IDENTICAL , T_IS_NOT_EQUAL , T_IS_NOT_IDENTICAL , T_IS_SMALLER_OR_EQUAL , T_MINUS_EQUAL , T_MOD_EQUAL , T_MUL_EQUAL , T_OR_EQUAL , T_PLUS_EQUAL , T_SL , T_SL_EQUAL , T_SR , T_SR_EQUAL , T_XOR_EQUAL , T_ELSE, T_ELSEIF, );
        $singleop = array('=', '+', '-', '/', '*', '^');
        foreach ($tokens as $index => $token) {
            if (!($token->isGivenKind($operator) || in_array($token->content, $singleop))) {
                continue;
            }

            $token->content = str_replace(' ', '', $token->content);

            $prevToken = $tokens[$index - 1];
            if ($prevToken->isWhitespace()) {
                $prevToken->content = ' ';
            } else {
                $token->content = ' '.$token->content;

            }

            $nextToken = $tokens[$index + 1];
            if ($nextToken->isWhitespace()) {
                $nextToken->content = ' ';
            } else {
                $token->content = $token->content.' ';
            }



        }

        return $tokens->generateCode();
    }

    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    public function getPriority()
    {
        return 0;
    }

    public function supports(\SplFileInfo $file)
    {
        return 'php' === pathinfo($file->getFilename(), PATHINFO_EXTENSION);
    }

    public function getName()
    {
        return 'space_two_sides';
    }

    public function getDescription()
    {
        return 'Space at two sides.';
    }
}
