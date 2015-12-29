<?php

return array(
    /*
    '\&' => '&',
    '\$' => '$',
    '\{' => '{',
    '\}' => '}',
    '\%' => '%',
    '\#' => '#',
    '\_' => '_',
    */

    // textogonekcentered
    // requires T1 encoding

    // \breve{} alias for \u

    '--' => '–', // ndash
    '---' => '—', // mdash

    '\ldots{}' => '…', // ellipsis

    '\P{}' => '¶',    // pilcrow
    '\S{}' => '§',    // section sign

    '\ae{}' => 'æ', // small ae ligature (diphthong)
    '\AE{}' => 'Æ', // capital ae ligature
    '\ss{}' => 'ß', // German sz ligature
    '\oe{}' => 'œ', // small oe ligature
    '\OE{}' => 'Œ', // capital OE ligature 
    '\o{}'  => 'ø', // small o, slash
    '\O{}'  => 'Ø', // capital O, slash
    '\AA{}' => 'Å', // ring above A
    '\aa{}' => 'å', // ring above A
    '\l{}'  => 'ł',  // l with stroke
    '\L{}'  => 'Ł',
    '\NG{}' => 'Ŋ',
    '\ng{}' => 'ŋ',

    // A (14), no: double grave, inv breve, ring below
    "\'{A}"  => 'Á', // acute
    '\H{A}'  => 'A̋', // double acute
    '\`{A}'  => 'À', // grave
    '\u{A}'  => 'Ă', // breve
    '\v{A}'  => 'Ǎ', // caron/hacek
    '\c{A}'  => 'A̧', // cedilla
    '\^{A}'  => 'Â', // circumflex
    '\"{A}'  => 'Ä', // umlaut
    '\.{A}'  => 'Ȧ', // dot above
    '\d{A}'  => 'Ạ', // dot below
    '\={A}'  => 'Ā', // macron
    '\k{A}'  => 'Ą', // ogonek
    '\r{A}'  => 'Å', // ring above
    '\~{A}'  => 'Ã', // tilde

    // a (14), no: double grave, inv breve, ring below
    "\'{a}"  => 'á', // acute
    '\H{a}'  => 'a̋', // double acute
    '\`{a}'  => 'à', // grave
    '\u{a}'  => 'ă', // breve
    '\v{a}'  => 'ǎ', // caron/hacek
    '\c{a}'  => 'a̧', // cedilla
    '\^{a}'  => 'â', // circumflex
    '\"{a}'  => 'ä', // umlaut
    '\.{a}'  => 'ȧ', // dot above
    '\d{a}'  => 'ạ', // dot below
    '\={a}'  => 'ā', // macron
    '\k{a}'  => 'ą', // ogonek
    '\r{a}'  => 'å', // ring above
    '\~{a}'  => 'ã', // tilde

    // B (3)
    '\.{B}'  => 'Ḃ', // dot above
    '\d{B}'  => 'Ḅ', // dot below
    '\={B}'  => 'Ḇ', // macron

    // b (3)
    '\.{b}'  => 'ḃ', // dot above
    '\d{b}'  => 'ḅ', // dot below
    '\={b}'  => 'ḇ', // marcon

    // C (7)
    "\'{C}"  => 'Ć', // acute
    '\v{C}'  => 'Č', // caron/hacek
    '\c{C}'  => 'Ç', // cedilla
    '\^{C}'  => 'Ĉ', // circumflex
    '\"{C}'  => 'C̈', // umlaut
    '\.{C}'  => 'Ċ', // dot above
    '\={C}'  => 'C̄', // macron

    // c (7)
    "\'{c}"  => 'ć', // acute
    '\v{c}'  => 'č', // caron/hacek
    '\c{c}'  => 'ç', // cedilla
    '\^{c}'  => 'ĉ', // circumflex
    '\"{c}'  => 'c̈', // umlaut
    '\.{c}'  => 'ċ', // dot above
    '\={c}'  => 'c̄', // macron

    // D (6)
    '\v{D}'  => 'Ď', // caron/hacek
    '\c{D}'  => 'Ḑ', // cedilla
    '\^{D}'  => 'Ḓ', // circumflex
    '\.{D}'  => 'Ḋ', // dot above
    '\d{D}'  => 'Ḍ', // dot below
    '\={D}'  => 'Ḏ', // macron

    // d (6)
    '\v{d}'  => 'ď', // caron/hacek
    '\c{d}'  => 'ḑ', // cedilla
    '\^{d}'  => 'ḓ', // circumflex
    '\.{d}'  => 'ḋ', // dot above
    '\d{d}'  => 'ḍ', // dot below
    '\={d}'  => 'ḏ', // macron

    // E (14), no: double grave, inv breve, circumflex below, tilde below
    "\'{E}"  => 'É', // acute
    '\H{E}'  => 'E̋', // dbl acute
    '\`{E}'  => 'È', // grave
    '\u{E}'  => 'Ĕ', // breve
    '\v{E}'  => 'Ě', // caron/hacek
    '\c{E}'  => 'Ȩ', // cedilla
    '\^{E}'  => 'Ê', // circumflex
    '\"{E}'  => 'Ë', // umlaut
    '\.{E}'  => 'Ė', // dot above
    '\d{E}'  => 'Ẹ', // dot below
    '\={E}'  => 'Ē', // macron
    '\k{E}'  => 'Ę', // ogonek
    '\r{E}'  => 'E̊', // ring above
    '\~{E}'  => 'Ẽ', // tilde

    // e (14), no: double grave, inv breve, circumflex below, tilde below
    "\\'{e}" => 'é', // acute
    '\H{e}'  => 'e̋', // double acute 
    '\`{e}'  => 'è', // grave
    '\u{e}'  => 'ĕ', // breve
    '\v{e}'  => 'ě', // caron/hacek
    '\c{e}'  => 'ȩ', // cedilla
    '\^{e}'  => 'ê', // circumflex
    '\"{e}'  => 'ë', // umlaut
    '\.{e}'  => 'ė', // dot above
    '\d{e}'  => 'ẹ', // dot below
    '\={e}'  => 'ē', // macron
    '\k{e}'  => 'ę', // ogonek
    '\r{e}'  => 'e̊', // ring above
    '\~{e}'  => 'ẽ', // tilde

    // F (2)
    '\v{F}'  => 'F̌', // caron/hacek
    '\.{F}'  => 'Ḟ', // dot above

    // f (2)
    '\v{f}'  => 'f̌', // caron/hacek
    '\.{f}'  => 'ḟ', // dot above

    // G (7)
    "\'{G}"  => 'Ǵ', // acute
    '\u{G}'  => 'Ğ', // breve
    '\v{G}'  => 'Ǧ', // caron/hacek
    '\c{G}'  => 'Ģ', // cedilla
    '\^{G}'  => 'Ĝ', // circumflex
    '\.{G}'  => 'Ġ', // dot above
    '\={G}'  => 'Ḡ', // macron

    // g (7)
    "\'{g}"  => 'ǵ', // acute
    '\u{g}'  => 'ğ', // breve
    '\v{g}'  => 'ǧ', // caron/hacek
    '\c{g}'  => 'ģ', // cedilla
    '\^{g}'  => 'ĝ', // circumflex
    '\.{g}'  => 'ġ', // dot above
    '\={g}'  => 'ḡ', // macron

    // H (8)
    '\u{H}'  => 'Ḫ', // breve
    '\v{H}'  => 'Ȟ', // caron/hacek
    '\c{H}'  => 'Ḩ', // cedilla
    '\^{H}'  => 'Ĥ', // circumflex
    '\"{H}'  => 'Ḧ', // umlaut
    '\.{H}'  => 'Ḣ', // dot above
    '\d{H}'  => 'Ḥ', // dot below
    '\={H}'  => 'H̱', // macron

    // h (8)
    '\u{h}'  => 'ḫ', // breve
    '\v{h}'  => 'ȟ', // caron/hacek
    '\c{h}'  => 'ḩ', // cedilla
    '\^{h}'  => 'ĥ', // circumflex
    '\"{h}'  => 'ḧ', // umlaut
    '\.{h}'  => 'ḣ', // dot above
    '\d{h}'  => 'ḥ', // dot below
    '\={h}'  => 'ẖ', // macron

    // I (12), no: double grave, inv breve, tilde below
    "\'{I}"  => 'Í', // acute
    '\`{I}'  => 'Ì', // grave
    '\u{I}'  => 'Ĭ', // breve
    '\v{I}'  => 'Ǐ', // caron/hacek
    '\c{I}'  => 'I̧', // cedilla
    '\^{I}'  => 'Î', // circumflex
    '\"{I}'  => 'Ï', // umlaut
    '\.{I}'  => 'İ', // dot above
    '\d{I}'  => 'Ị', // dot below
    '\={I}'  => 'Ī', // macron
    '\k{I}'  => 'Į', // ogonek
    '\~{I}'  => 'Ĩ', // tilde

    // i (11), np: double grave, inv breve, tilde below
    "\\'{i}" => 'í', // acute
    '\`{i}'  => 'ì', // grave
    '\u{i}'  => 'ĭ', // breve
    '\v{i}'  => 'ǐ', // caron/hacek
    '\c{i}'  => 'i̧', // cedilla
    '\^{i}'  => 'î', // circumflex
    '\"{i}'  => 'ï', // umlaut
    '\d{i}'  => 'ị', // dot below
    '\={i}'  => 'ī', // macron
    '\k{i}'  => 'į', // ogonek
    '\~{i}'  => 'ĩ', // tilde
    '\i{}'   => 'ı',

    '\IJ{}'  => 'Ĳ',
    '\ij{}'  => 'ĳ',

    // J (6)
    "\'{J}"  => 'J́', // acute
    '\v{J}'  => 'J̌', // caron/hacek
    '\^{J}'  => 'Ĵ', // circumflex
    '\d{J}'  => 'J̣', // dot below
    '\={J}'  => 'J̄', // macron
    '\~{J}'  => 'J̃', // tilde
    // Ɉ LATIN CAPITAL LETTER J WITH STROKE

    // dotless j (1)
    '\j{}'   => 'ȷ', // dotless J
    // ɟ Dotless J with stroke
    // ʄ Dotless J with stroke and hook

    // j
    "\'{j}"  => 'j́', // acute
    '\v{j}'  => 'ǰ', // caron/hacek
    '\^{j}'  => 'ĵ', // circumflex
    '\d{j}'  => 'j̣', // dot below
    '\={j}'  => 'j̄', // macron
    '\~{j}'  => 'j̃', // tilde
    // ɉ j with stroke
    // ʝ J with crossed-tail

    // K
//"Ḱ","K with acute"],["ḱ","k with acute"],["K̀","K with grave"],["k̀","k with grave"],["Ǩ","K with caron"],["ǩ","k with caron"],["K̄","K with macron"],["k̄","k with macron"],["K̇","K with dot above"],["k̇","k with dot above"],["Ķ","K with cedilla"],["ķ","k with cedilla"],["Ḳ","K with dot below"],["ḳ","k with dot below"],["Ḵ","K with line below"],["ḵ","k with line below"],["ᶄ","K with palatal hook"],["Ƙ","K with hook"],["ƙ","k with hook"],["Ⱪ","K with descender"],["ⱪ","k with descender"],["Ꝁ","K with stroke"],["ꝁ","k with stroke"],["Ꝃ","K with diagonal stroke"],["ꝃ","k with diagonal stroke"],["Ꝅ","K with stroke and diagonal stroke"],["ꝅ","k with stroke and diagonal stroke"],["Ꞣ","K with oblique stroke"],["ꞣ","k with oblique stroke"]

    // L
// ["Ĺ","L with acute"],["ĺ","l with acute"],["L̂","L with circumflex"],["l̂","l with circumflex"],["L̐","L with chandrabindu"],["l̐","l with chandrabindu"],["L̃","L with tilde"],["l̃","l with tilde"],["L̓","L with comma above"],["l̓","l with comma above"],["Ľ","L with caron"],["ľ","l with caron"],["Ļ","L with cedilla"],["ļ","l with cedilla"],["L̦","L with comma below"],["l̦","l with comma below"],["Ḷ","L with dot below"],["ḷ","l with dot below"],["Ḹ","L with dot below and macron"],["ḹ","l with dot below and macron"],["Ḷ́","L with acute and dot below"],["ḷ́","l with acute and dot below"],["Ḷ̓","L with comma above and dot below"],["ḷ̓","l with comma above and dot below"],["Ḽ","L with circumflex below"],["ḽ","l with circumflex below"],["Ḻ","L with line below"],["ḻ","l with line below"],["Ł","L with stroke"],["ł","l with stroke"],["Ꝉ","L with high stroke"],["ꝉ","l with high stroke"],["Ƚ","L with bar"],["ƚ","l with bar"],["Ⱡ","L with double bar"],["ⱡ","l with double bar"],["Ɫ","L with middle tilde"],["ɫ","l with middle tilde"],["ɬ","L with belt"],["ᶅ","L with palatal hook"],["ɭ","L with retroflex hook"],["ꞎ","L with retroflex hook and belt"],["ȴ","L with curl"],["ƛ","Lambda with stroke"],["ƛ̓","Lambda with stroke and comma above"]

    // M
//["Ḿ","M with acute"],["ḿ","m with acute"],["M̀","M with grave"],["m̀","m with grave"],["Ṁ","M with dot above"],["ṁ","m with dot above"],["Ṃ","M with dot below"],["ṃ","m with dot below"],["M̍","M with vertical line"],["m̍","m with vertical line"],["M̄","M with macron"],["m̄","m with macron"],["M̐","M with chandrabindu"],["m̐","m with chandrabindu"],["M̃","M with tilde"],["m̃","m with tilde"],["M̈","M with diaeresis"],["m̈","m with diaeresis"],["M̓","M with comma above"],["m̓","m with comma above"],["ᵯ","M with middle tilde"],["M̧","M with cedilla"],["m̧","m with cedilla"],["M̨","M with ogonek"],["m̨","m with ogonek"],["Ṃ","M with dot below"],["ṃ","m with dot below"],["Ṃ́","M with acute and dot below"],["ṃ́","m with acute and dot below"],["Ṃ̓","M with comma above and dot below"],["ṃ̓","m with comma above and dot below"],["M̦","M with comma below"],["m̦","m with comma below"],["ᶆ","M with palatal hook"],["Ɱ","M with hook"],["ɱ","m with hook"]

    // N
// ["Ń","N with acute"],["ń","n with acute"],["Ǹ","N with grave"],["ǹ","n with grave"],["N̂","N with circumflex"],["n̂","n with circumflex"],["Ň","N with caron"],["ň","n with caron"],["Ñ","N with tilde"],["ñ","n with tilde"],["Ñ̈","N with tidle and diaeresis"],["ñ̈","n with tidle and diaeresis"],["Ṅ","N with dot above"],["ṅ","n with dot above"],["N̍","N with vertical line"],["n̍","n with vertical line"],["N̄","N with macron"],["n̄","n with macron"],["N̐","N with chandrabindu"],["n̐","n with chandrabindu"],["N̈","N with diaresis"],["n̈","n with diaresis"],["Ꞥ","N with oblique stroke"],["ꞥ","n with oblique stroke"],["ᵰ","N with middle tilde"],["Ņ","N with cedilla"],["ņ","n with cedilla"],["Ṋ","N with circumflex below"],["ṋ","n with circumflex below"],["N̦","N with comma below"],["n̦","n with comma below"],["Ṇ","N with dot below"],["ṇ","n with dot below"],["Ṇ́","N with acute and dot below"],["ṇ́","n with acute and dot below"],["Ṇ̓","N with comma above and dot below"],["ṇ̓","n with comma above and dot below"],["Ṉ","N with line below"],["ṉ","n with line below"],["N̰","N with tilde below"],["n̰","n with tilde below"],["N̲","N with underline"],["n̲","n with underline"],["Ɲ","N with left hook"],["ɲ","n with left hook"],["Ƞ","N with long right leg"],["ƞ","n with long right leg"],["Ꞑ","N with descender"],["ꞑ","n with descender"],["ᶇ","N with palatal hook"],["ɳ","N with retroflex hook"],["ȵ","N with curl"]

    // O
// "Ó","O with acute"],["ó","o with acute"],["Ò","O with grave"],["ò","o with grave"],["Ŏ","O with breve"],["ŏ","o with breve"],["Ô","O with circumflex"],["ô","o with circumflex"],["Ố","O with circumflex and acute"],["ố","o with circumflex and acute"],["Ồ","O with circumflex and grave"],["ồ","o with circumflex and grave"],["Ỗ","O with circumflex and tilde"],["ỗ","o with circumflex and tilde"],["Ổ","O with circumflex and hook above"],["ổ","o with circumflex and hook above"],["Ǒ","O with caron"],["ǒ","o with caron"],["O̐","O with chandrabindu"],["o̐","o with chandrabindu"],["Ö","O with diaeresis"],["ö","o with diaeresis"],["Ö́","O with diaeresis and acute"],["ö́","o with diaeresis and acute"],["Ö̀","O with diaeresis and grave"],["ö̀","o with diaeresis and grave"],["Ȫ","O with diaeresis and macron"],["ȫ","o with diaeresis and macron"],["Ő","O with double acute"],["ő","o with double acute"],["Õ","O with tilde"],["õ","o with tilde"],["Ṍ","O with tilde and acute"],["ṍ","o with tilde and acute"],["Ṏ","O with tilde and diaeresis"],["ṏ","o with tilde and diaeresis"],["Ȭ","O with tilde and macron"],["ȭ","o with tilde and macron"],["Ȯ","O with dot above"],["ȯ","o with dot above"],["Ȱ","O with dot above and macron"],["ȱ","o with dot above and macron"],["O͘","O with dot above right"],["o͘","o with dot above right"],["Ó͘","O with dot above right and acute"],["ó͘","o with dot above right and acute"],["Ò͘","O with dot above right and grave"],["ò͘","o with dot above right and grave"],["Ō͘","O with dot above right and macron"],["ō͘","o with dot above right and macron"],["O̍͘","O with dot above and vertical line"],["o̍͘","o with dot above and vertical line"],["Ø","O with stroke"],["ø","o with stroke"],["Ǿ","O with stroke and acute"],["ǿ","o with stroke and acute"],["Ø̀","O with stroke and grave"],["ø̀","o with stroke and grave"],["Ø̂","O with stroke and circumflex"],["ø̂","o with stroke and circumflex"],["Ø̌","O with stroke and caron"],["ø̌","o with stroke and caron"],["Ø̄","O with stroken and macron"],["ø̄","o with stroken and macron"],["Ɵ","O with bar"],["ɵ","o with bar"],["Ꝋ","O with long stroke overlay"],["ꝋ","o with long stroke overlay"],["Ǫ","O with ogonek"],["ǫ","o with ogonek"],["Ǭ","O with macron and ogonek"],["ǭ","o with macron and ogonek"],["Ǭ̀","O with macron, grave and ogonek"],["ǭ̀","o with macron, grave and ogonek"],["Ǫ́","O with acute and ogonek"],["ǫ́","o with acute and ogonek"],["Ǫ̀","O with grave and ogonek"],["ǫ̀","o with grave and ogonek"],["Ǫ̂","O with circumflex and ogonek"],["ǫ̂","o with circumflex and ogonek"],["Ǫ̌","O with caron and ogonek"],["ǫ̌","o with caron and ogonek"],["Ō","O with macron"],["ō","o with macron"],["Ṓ","O with macron and acute"],["ṓ","o with macron and acute"],["Ṑ","O with macron and grave"],["ṑ","o with macron and grave"],["Ō̂","O with macron and circumflex"],["ō̂","o with macron and circumflex"],["Ō̌","O with macron and caron"],["ō̌","o with macron and caron"],["Ỏ","O with hook above"],["ỏ","o with hook above"],["Ő","O with double acute"],["ő","o with double acute"],["Ȍ","O with double grave"],["ȍ","o with double grave"],["Ȏ","O with inverted breve"],["ȏ","o with inverted breve"],["Ơ","O with horn"],["ơ","o with horn"],["Ớ","O with horn and acute"],["ớ","o with horn and acute"],["Ờ","O with horn and grave"],["ờ","o with horn and grave"],["Ỡ","O with horn and tilde"],["ỡ","o with horn and tilde"],["Ở","O with horn and hook above"],["ở","o with horn and hook above"],["O̍","O with vertical line"],["o̍","o with vertical line"],["Ọ","O with dot below"],["ọ","o with dot below"],["Ọ́","O with acute and dot below"],["ọ́","o with acute and dot below"],["Ọ̀","O with grave and dot below"],["ọ̀","o with grave and dot below"],["Ộ","O with circumflex and dot below"],["ộ","o with circumflex and dot below"],["Ọ̄","O with macron and dot below"],["ọ̄","o with macron and dot below"],["Ợ","O with horn and dot below"],["ợ","o with horn and dot below"],["Ộ","O with circumflex and dot below"],["ộ","o with circumflex and dot below"],["O̭","O with circumflex below"],["o̭","o with circumflex below"],["O̧","O with cedilla"],["o̧","o with cedilla"],["Ó̧","O with acute and cedilla"],["ó̧","o with acute and cedilla"],["Ò̧","O with grave and cedilla"],["ò̧","o with grave and cedilla"],["Ô̧","O with circumflex and cedilla"],["ô̧","o with circumflex and cedilla"],["Ǒ̧","O with caron and cedilla"],["ǒ̧","o with caron and cedilla"],["O̱","O with line below"],["o̱","o with line below"],["Ó̱","O with acute and line below"],["ó̱","o with acute and line below"],["Ò̱","O with grave and line below"],["ò̱","o with grave and line below"],["Ô̱","O with circumflex and line below"],["ô̱","o with circumflex and line below"],["Ō̱","O with macron and line below"],["ō̱","o with macron and line below"],["Ö̱","O with diaeresis and line below"],["ö̱","o with diaeresis and line below"],["O̲","O with underline"],["o̲","o with underline"],["ᴓ","Sideways O with stroke"],["ᶗ","Open O with retroflex hook"],["Ꝍ","O with loop"],["ꝍ","o with loop"],["ⱺ","O with low ring inside"],["Ꝋ","O with long stroke overlay"],["ꝋ","o with long stroke overlay"],["Ɔ́","Open O with acute"],["ɔ́","open O with acute"],["Ɔ̀","Open O with grave"],["ɔ̀","open O with grave"],["Ɔ̂","Open O with circumflex"],["ɔ̂","open O with circumflex"],["Ɔ̌","Open O with caron"],["ɔ̌","open O with caron"],["Ɔ̄","Open O with macron"],["ɔ̄","open O with macron"],["Ɔ̃","Open O with tilde"],["ɔ̃","open O with tilde"],["Ɔ̃́","Open O with tilde and acute"],["ɔ̃́","open O with tilde and acute"],["Ɔ̃̀","Open O with tilde and grave"],["ɔ̃̀","open O with tilde and grave"],["Ɔ̃̂","Open O with tilde and circumflex"],["ɔ̃̂","open O with tilde and circumflex"],["Ɔ̃̌","Open O with tilde and caron"],["ɔ̃̌","open O with tilde and caron"],["Ɔ̃̍","Open O with tilde and verticale line"],["ɔ̃̍","open O with tilde and verticale line"],["Ɔ̈","Open O with diaeresis"],["ɔ̈","open O with diaeresis"],["Ɔ̍","Open O with vertical line"],["ɔ̍","open O with vertical line"],["Ɔ̧","Open O with cedilla"],["ɔ̧","open O with cedilla"],["Ɔ̧́","Open O with acute and cedilla"],["ɔ̧́","open O with acute and cedilla"],["Ɔ̧̀","Open O with grave and cedilla"],["ɔ̧̀","open O with grave and cedilla"],["Ɔ̧̂","Open O with circumflex and cedilla"],["ɔ̧̂","open O with circumflex and cedilla"],["Ɔ̧̌","Open O with caron and cedilla"],["ɔ̧̌","open O with caron and cedilla"],["Ɔ̱","Open O with tilde below"],["ɔ̱","open O with tilde below"]

    // P
// ["Ṕ","P with acute"],["ṕ","p with acute"],["P̀","P with grave"],["p̀","p with grave"],["Ṗ","P with dot above"],["ṗ","p with dot above"],["P̣","P with dot above below"],["p̣","p with dot above below"],["P̄","P with macron"],["p̄","p with macron"],["P̓","P with comma above"],["p̓","p with comma above"],["P̈","P with diaeresis"],["p̈","p with diaeresis"],["P̤","P with diaeresis below"],["p̤","p with diaeresis below"],["P̄","P with tilde"],["p̄","p with tilde"],["Ᵽ","P with stroke"],["ᵽ","p with stroke"],["Ꝑ","P with stroke through descender"],["ꝑ","p with stroke through descender"],["ᵱ","P with middle tilde"],["ᶈ","P with palatal hook"],["Ƥ","P with hook"],["ƥ","p with hook"],["Ꝓ","P with flourish"],["ꝓ","p with flourish"],["Ꝕ","P with squirrel tail"],["ꝕ","p with squirrel tail"],

    // Q
// ["Q̓","Q with comma above"],["q̓","q with comma above"],["Q̇","Q with dot above"],["q̇","q with dot above"],["Ꝗ","Q with stroke through descender"],["ꝗ","q with stroke through descender"],["Ꝙ","Q with diagonal stroke"],["ꝙ","q with diagonal stroke"],["ʠ","Q with hook"],["Ɋ","Q with hook tail"],["ɋ","q with hook tail"]

    // R
// ["Ŕ","R with acute"],["ŕ","r with acute"],["Ř","R with caron"],["ř","r with caron"],["R̂","R with circumflex"],["r̂","r with circumflex"],["R̓","R with comma above"],["r̓","r with comma above"],["R̦","R with comma below"],["r̦","r with comma below"],["Ṙ","R with dot above"],["ṙ","r with dot above"],["Ŗ","R with cedilla"],["ŗ","r with cedilla"],["R̄","R with macron"],["r̄","r with macron"],["Ꞧ","R with oblique stroke"],["ꞧ","r with oblique stroke"],["Ȑ","R with double grave"],["ȑ","r with double grave"],["Ȓ","R with inverted breve"],["ȓ","r with inverted breve"],["Ṛ","R with dot below"],["ṛ","r with dot below"],["Ṝ","R with dot below and macron"],["ṝ","r with dot below and macron"],["R̰","R with diaeresis below"],["r̰","r with diaeresis below"],["Ṟ","R with line below"],["ṟ","r with line below"],["R̥","R with ring below"],["r̥","r with ring below"],["R̥̄","R with ring below and macron"],["r̥̄","r with ring below and macron"],["R̃","R with tilde"],["r̃","r with tilde"],["Ɍ","R with stroke"],["ɍ","r with stroke"],["ᵲ","R with middle tilde"],["ɺ","Turned R with long leg"],["ᶉ","R with palatal hook"],["ɻ","Turned R with hook"],["ⱹ","Turned R with tail"],["ɼ","R with long leg"],["Ɽ","R with tail"],["ɽ","r with tail"],["ɾ","R with fishhook"],["ᵳ","R with fishhook and middle tilde"],["ɿ","Reversed R with fishhook"]

    // S
// ["Ś","S with acute"],["ś","s with acute"],["Ṥ","S with acute and dot above"],["ṥ","s with acute and dot above"],["Ŝ","S with circumflex"],["ŝ","s with circumflex"],["Š","S with caron"],["š","s with caron"],["Ṧ","S with caron and dot above"],["ṧ","s with caron and dot above"],["Ṡ","S with dot above"],["ṡ","s with dot above"],["Ş","S with cedilla"],["ş","s with cedilla"],["Ꞩ","S with oblique stroke"],["ꞩ","s with oblique stroke"],["Ṣ","S with dot below"],["ṣ","s with dot below"],["Ṩ","S with dot below and dot above"],["ṩ","s with dot below and dot above"],["Ș","S with comma below"],["ș","s with comma below"],["ẛ","Long s with dot above"],["ẞ","S with middle tilde"],["ᵴ","s with middle tilde"],["ᶊ","S with palatal hook"],["ʂ","S with hook"],["Ȿ","S with swash tail"],["ȿ","s with swash tail"],["ẜ","Long S with diagonal stroke"],["ẝ","Long S with high stroke"],["ᶋ","Esh with palatal hook"],["ᶘ","Esh with retroflex hook"],["ʆ","Esh with curl"],

    // T
// ["Ť","T with caron"],["ť","t with caron"],["T̈","T with diaeresis"],["ẗ","t with diaeresis"],["Ṫ","T with dot above"],["ṫ","t with dot above"],["Ţ","T with cedilla"],["ţ","t with cedilla"],["Ṭ","T with dot below"],["ṭ","t with dot below"],["Ț","T with comma below"],["ț","t with comma below"],["Ṱ","T with circumflex below"],["ṱ","t with circumflex below"],["Ṯ","T with line below"],["ṯ","t with line below"],["ƾ","Inverted glottal stop with stroke"],["Ŧ","T with stroke"],["ŧ","t with stroke"],["Ⱦ","T with diagonal stroke"],["ⱦ","t with diagonal stroke"],["ᵵ","T with middle tilde"],["ƫ","T with palatal hook"],["Ƭ","T with hook"],["ƭ","t with hook"],["Ʈ","T with retroflex hook"],["ʈ","t with retroflex hook"],["ȶ","T with curl"]

    // U
// ["Ú","U with acute"],["ú","u with acute"],["Ù","U with grave"],["ù","u with grave"],["Ŭ","U with breve"],["ŭ","u with breve"],["Û","U with circumflex"],["û","u with circumflex"],["Ǔ","U with caron"],["ǔ","u with caron"],["Ů","U with ring above"],["ů","u with ring above"],["Ü","U with diaeresis"],["ü","u with diaeresis"],["Ǘ","U with diaeresis and acute"],["ǘ","u with diaeresis and acute"],["Ǜ","U with diaeresis and grave"],["ǜ","u with diaeresis and grave"],["Ǚ","U with diaeresis and caron"],["ǚ","u with diaeresis and caron"],["Ǖ","U with diaeresis and macron"],["ǖ","u with diaeresis and macron"],["Ű","U with double acute"],["ű","u with double acute"],["Ũ","U with tilde"],["ũ","u with tilde"],["Ṹ","U with tilde and acute"],["ṹ","u with tilde and acute"],["Ų","U with ogonek"],["ų","u with ogonek"],["Ū","U with macron"],["ū","u with macron"],["Ṻ","U with macron and diaeresis"],["ṻ","u with macron and diaeresis"],["Ủ","U with hook above"],["ủ","u with hook above"],["Ȕ","U with double grave"],["ȕ","u with double grave"],["Ȗ","U with inverted breve"],["ȗ","u with inverted breve"],["Ư","U with horn"],["ư","u with horn"],["Ứ","U with horn and acute"],["ứ","u with horn and acute"],["Ừ","U with horn and grave"],["ừ","u with horn and grave"],["Ữ","U with horn and tilde"],["ữ","u with horn and tilde"],["Ử","U with horn and hook above"],["ử","u with horn and hook above"],["Ự","U with horn and dot below"],["ự","u with horn and dot below"],["Ụ","U with dot below"],["ụ","u with dot below"],["Ṳ","U with diaeresis below"],["ṳ","u with diaeresis below"],["Ṷ","U with circumflex below"],["ṷ","u with circumflex below"],["Ṵ","U with tilde below"],["ṵ","u with tilde below"],["Ʉ","U bar"],["ʉ","u bar"],["ᵾ","Small capital U with stroke"],["ᶙ","U with retroflex hook"]

    // V
// ,["Ṽ","V with tilde"],["ṽ","v with tilde"],["Ṿ","V with dot below"],["ṿ","v with dot below"],["Ꝟ","V with diagonal stroke"],["ꝟ","v with diagonal stroke"],["ᶌ","V with palatal hook"],["Ʋ","V with hook (Script V)"],["ʋ","v with hook (Script V)"],["ⱱ","V with right hook"],["ⱴ","V with curl"]

    // W
// ["Ẃ","W with acute"],["ẃ","w with acute"],["Ẁ","W with grave"],["ẁ","w with grave"],["Ŵ","W with circumflex"],["ŵ","w with circumflex"],["W̊","W with ring above"],["ẘ","w with ring above"],["Ẅ","W with diaeresis"],["ẅ","w with diaeresis"],["Ẇ","W with dot above"],["ẇ","w with dot above"],["Ẉ","W with dot below"],["ẉ","w with dot below"],["Ⱳ","W with hook"],["ⱳ","w with hook"],

    // X
// ["Ẍ","X with diaeresis"],["ẍ","x with diaeresis"],["Ẋ","X with dot above"],["ẋ","x with dot above"],["ᶍ","X with palatal hook"],

    // Y
// ["Ý","Y with acute"],["ý","y with acute"],["Ỳ","Y with grave"],["ỳ","y with grave"],["Ŷ","Y with circumflex"],["ŷ","y with circumflex"],["Y̊","Y with ring above"],["ẙ","y with ring above"],["Ÿ","Y with diaeresis"],["ÿ","y with diaeresis"],["Ỹ","Y with tilde"],["ỹ","y with tilde"],["Ẏ","Y with dot above"],["ẏ","y with dot above"],["Ȳ","Y with macron"],["ȳ","y with macron"],["Ỷ","Y with hook above"],["ỷ","y with hook above"],["Ỵ","Y with dot below"],["ỵ","y with dot below"],["Ɏ","Y with stroke"],["ɏ","y with stroke"],["Ƴ","Y with hook"],["ƴ","y with hook"],["Ỿ","Y with loop"],["ỿ","y with loop"],

    // Z
    "\'{Z}" => 'Ź', // Z with acute
    "\'{z}" => 'ź', // z with acute

    '\^{Z}' => 'Ẑ', // Z with circumflex
    '\^{z}' => 'ẑ', // z with circumflex

    '\v{Z}' => 'Ž', // Z with caron
    '\v{z}' => 'ž', // z with caron

    '\.{Z}' => 'Ż', // Z with dot above
    '\.{z}' => 'ż', // z with dot above

    '\d{Z}' => 'Ẓ', // Z with dot below
    '\d{z}' => 'ẓ', // z with dot below

    // ["Ẕ","Z with line below"],["ẕ","z with line below"],["Ƶ","Z with stroke"],["ƶ","z with stroke"],["ᵶ","Z with middle tilde"],["ᶎ","Z with palatal hook"],["Ȥ","Z with hook"],["ȥ","z with hook"],["ʐ","Z with retroflex hook"],["ʑ","Z with curl"],["Ɀ","Z with swash tail"],["ɀ","z with swash tail"],["Ⱬ","Z with descender"],["ⱬ","z with descender"],["Ǯ","Ezh with caron"],["ǯ","ezh with caron"],["ᶚ","Ezh with retroflex hook"],["ƺ","Ezh with tail"],["ʓ","Ezh with curl"],

    '\`{o}' => 'ò',  // grave
    "\'{o}" => 'ó', // acute
    '\^{o}' => 'ô',  // circumflex
    '\"{o}' => 'ö',  // umlaut, trema or dieresis
    '\H{o}' => 'ő',  // long Hungarian umlaut (double acute)
    '\~{o}' => 'õ',  // tilde

    '\={o}' => 'ō',  // macron accent (a bar over the letter)
    '\b{o}' => 'o̲',  // bar under the letter
    '\.{o}' => 'ȯ',  // dot over the letter
    '\d{u}' => 'ụ',  // dot under the letter
    '\u{o}' => 'ŏ',  // breve over the letter
    '\v{s}' => 'š',  // caron/hacek ("v") over the letter
/*
    "\\'{}" => '', // acute
    '\H{}'  => '', // double acute
    '\`{}'  => '', // grave
    '\u{}'  => '', // breve
    '\v{}'  => '', // caron/hacek
    '\c{}'  => '', // cedilla
    '\^{}'  => '', // circumflex
    '\"{}'  => '', // umlaut
    '\.{}'  => '', // dot above
    '\d{}'  => '', // dot below
    '\={}'  => '', // macron
    '\k{}'  => '', // ogonek
    '\r{}'  => '', // ring above
    '\~{}'  => '', // tilde
*/

    // '\TH{}' => 'Þ',
    // '\DH{}' => 'Ð', // Command \DH unavailable in encoding OT1.
    // '\dh{}' => 'ð',
    // '\dj{}' => 'đ',

    '\--'   => '‒',
    '\---'  => '—',

// ["Ꝥ","Thorn with stroke"],["ꝥ","thorn with stroke"],["Ꝧ","Thorn with stroke through descender"],["ꝧ","thorn with stroke through descender"],["ƻ","Two with stroke"],["Ꜯ","Cuatrillo with comma"],["ꜯ","cuatrillo with comma"],["ʡ","Glottal stop with stroke"],["ʢ","Reversed glottal stop with stroke"]]

    '$\alpha$'      => 'α',
    '$\beta$'       => 'β',
    '$\gamma$'      => 'γ',
    '$\delta$'      => 'δ',
    '$\varepsilon$' => 'ε',
    '$\zeta$'       => 'ζ',
    '$\eta$'        => 'η',
    '$\vartheta$'   => 'θ',
    '$\iota$'       => 'ι',
    '$\kappa$'      => 'κ',
    '$\lambda$'     => 'λ',
    '$\mu$'         => 'μ',
    '$\nu$'         => 'ν',
    '$\xi$'         => 'ξ',
    '$\omicron$'    => 'ο',
    '$\pi$'         => 'π',
    '$\varrho$'     => 'ρ',
    '$\varsigma$'   => 'ς',
    '$\sigma$'      => 'σ',
    '$\tau$'        => 'τ',
    '$\upsilon$'    => 'υ',
    '$\varphi$'     => 'φ',
    '$\chi$'        => 'χ',
    '$\psi$'        => 'ψ',
    '$\omega$'      => 'ω',
    '$\Alpha$'      => 'Α',
    '$\Beta$'       => 'Β',
    '$\Gamma$'      => 'Γ',
    '$\Delta$'      => 'Δ',
    '$\Epsilon$'    => 'Ε',
    '$\Zeta$'       => 'Ζ',
    '$\Eta$'        => 'Η',
    '$\Theta$'      => 'Θ',
    '$\Iota$'       => 'Ι',
    '$\Kappa$'      => 'Κ',
    '$\Lambda$'     => 'Λ',
    '$\Mu$'         => 'Μ',
    '$\Nu$'         => 'Ν',
    '$\Xi$'         => 'Ξ',
    '$\Omicron$'    => 'Ο',
    '$\Pi$'         => 'Π',
    '$\Rho$'        => 'Ρ',
    '$\Sigma$'      => 'Σ',
    '$\Tau$'        => 'Τ',
    '$\Upsilon$'    => 'Υ',
    '$\Phi$'        => 'Φ',
    '$\Chi$'        => 'Χ',
    '$\Psi$'        => 'Ψ',
    '$\Omega$'      => 'Ω',
);
