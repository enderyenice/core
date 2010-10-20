<?php
/**
 * This file is part of PHP_Depend.
 *
 * PHP Version 5
 *
 * Copyright (c) 2008-2010, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    PHP_Depend
 * @subpackage Parser
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.pdepend.org/
 * @since      0.9.20
 */

require_once 'PHP/Depend/Parser.php';
require_once 'PHP/Depend/Parser/TokenStreamEndException.php';
require_once 'PHP/Depend/Parser/UnexpectedTokenException.php';

/**
 * Concrete parser implementation that is very tolerant and accepts language
 * constructs and keywords that are reserved in newer php versions, but not in
 * older versions.
 *
 * @category   PHP
 * @package    PHP_Depend
 * @subpackage Parser
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.pdepend.org/
 * @since      0.9.20
 */
class PHP_Depend_Parser_VersionAllParser extends PHP_Depend_Parser
{
    /**
     * Parses a valid class or interface name and returns the image of the parsed
     * token.
     *
     * @return string
     * @throws PHP_Depend_Parser_TokenStreamEndException When the current token
     *         stream does not contain one more token.
     * @throws PHP_Depend_Parser_UnexpectedTokenException When the next available
     *         token is not a valid class name.
     */
    protected function parseClassName()
    {
        $type = $this->tokenizer->peek();
        switch ($type) {

        case self::T_USE:
        case self::T_NULL:
        case self::T_TRUE:
        case self::T_FALSE:
        case self::T_STRING:
        case self::T_NAMESPACE:
            return $this->consumeToken($type)->image;

        case self::T_EOF:
            throw new PHP_Depend_Parser_TokenStreamEndException($this->tokenizer);
        }

        throw new PHP_Depend_Parser_UnexpectedTokenException(
            $this->tokenizer->next(),
            $this->tokenizer->getSourceFile()
        );
    }
}