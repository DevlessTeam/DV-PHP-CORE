ace.define("ace/mode/php", ["require", "exports", "module", "ace/lib/oop", "ace/mode/text", "ace/tokenizer", "ace/mode/php_highlight_rules", "ace/mode/matching_brace_outdent", "ace/range", "ace/mode/behaviour/cstyle", "ace/mode/folding/cstyle", "ace/unicode"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("./text").Mode,
        s = e("../tokenizer").Tokenizer,
        o = e("./php_highlight_rules").PhpHighlightRules,
        u = e("./matching_brace_outdent").MatchingBraceOutdent,
        a = e("../range").Range,
        f = e("./behaviour/cstyle").CstyleBehaviour,
        l = e("./folding/cstyle").FoldMode,
        c = e("../unicode"),
        h = function() {
            this.$tokenizer = new s((new o).getRules()), this.$outdent = new u, this.$behaviour = new f, this.foldingRules = new l
        };
    r.inherits(h, i),
        function() {
            this.tokenRe = new RegExp("^[" + c.packages.L + c.packages.Mn + c.packages.Mc + c.packages.Nd + c.packages.Pc + "_]+", "g"), this.nonTokenRe = new RegExp("^(?:[^" + c.packages.L + c.packages.Mn + c.packages.Mc + c.packages.Nd + c.packages.Pc + "_]|s])+", "g"), this.toggleCommentLines = function(e, t, n, r) {
                var i = !0,
                    s = /^(\s*)#/;
                for (var o = n; o <= r; o++)
                    if (!s.test(t.getLine(o))) {
                        i = !1;
                        break
                    }
                if (i) {
                    var u = new a(0, 0, 0, 0);
                    for (var o = n; o <= r; o++) {
                        var f = t.getLine(o),
                            l = f.match(s);
                        u.start.row = o, u.end.row = o, u.end.column = l[0].length, t.replace(u, l[1])
                    }
                } else t.indentRows(n, r, "#")
            }, this.getNextLineIndent = function(e, t, n) {
                var r = this.$getIndent(t),
                    i = this.$tokenizer.getLineTokens(t, e),
                    s = i.tokens,
                    o = i.state;
                if (s.length && s[s.length - 1].type == "comment") return r;
                if (e == "php-start") {
                    var u = t.match(/^.*[\{\(\[\:]\s*$/);
                    u && (r += n)
                } else if (e == "php-doc-start") {
                    if (o != "php-doc-start") return "";
                    var u = t.match(/^\s*(\/?)\*/);
                    u && (u[1] && (r += " "), r += "* ")
                }
                return r
            }, this.checkOutdent = function(e, t, n) {
                return this.$outdent.checkOutdent(t, n)
            }, this.autoOutdent = function(e, t, n) {
                this.$outdent.autoOutdent(t, n)
            }
        }.call(h.prototype), t.Mode = h
}), ace.define("ace/mode/php_highlight_rules", ["require", "exports", "module", "ace/lib/oop", "ace/lib/lang", "ace/mode/doc_comment_highlight_rules", "ace/mode/text_highlight_rules", "ace/mode/html_highlight_rules"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("../lib/lang"),
        s = e("./doc_comment_highlight_rules").DocCommentHighlightRules,
        o = e("./text_highlight_rules").TextHighlightRules,
        u = e("./html_highlight_rules").HtmlHighlightRules,
        a = function() {
            var e = s,
                n = i.arrayToMap("firstly|secondly|thirdly|beSureTo|lastly|next|then|also|convertToUpperCase|otherwise|elseWhenever|whenever|onQuery|onUpdate|onCreate|onDelete|beforeQuerying|beforeCreating|beforeUpdating|beforeDeleting|afterQuerying|afterCreating|afterUpdating|afterDeleting|done|end|onAnyRequest|$EVENT|mutateResponse|fetchExcept|endWhenever|removeElementFromCollection|import|addElementToCollection|createRelationship|apply|findNReplace|appendCollectionTo|appendCollectionToRelated|getLastCharacter|getLastButOneCharacter|truncateString|countWords|countCharacters|removeElementFromCollection|addElementToCollection|useCollectionAsKeys|expandCollection|findCollectionDiffs|checkIfCollectionContains|applyOnElement|reverseString".split("|")),
                // r = i.arrayToMap("die|echo|empty|exit|eval|include|include_once|isset|list|require|require_once|return|print|unset".split("|")),
                o = i.arrayToMap("truncateString|evaluate|getCharacter|getThirdCharacter|getSecondCharacter|getFirstCharacter|concatenate|mutateResponsePayload|mutateResponseMessage|mutateStatusCode|percentOf|roundUp|getSquareRoot|end|done|findSquareRootOf|divideBy|divide|multiply|subtract|sumUp|calculate|getResponsePayload|getResponseMessage|getStatusCode|import|getResponse|generateUniqueId|generateRandomString|generateRandomAlphanums|appendCollectionToRelated|generateRandomInteger|getTimestamp|getCurrentYear|getCurrentMonth|getCurrentDay|getCurrentHour|removeElementFromCollection|getFormattedDate|getCurrentSecond|getCurrentMinute|removeElementFromCollection|addElementToCollection|checkIfCollectionContains|findCollectionDiffs|reverseString|useCollectionAsKeys|expandCollection|paginateCollection|reduceNumberOfElementsTo|offsetCollectionBy|sortCollectionBy|reverseTheCollection|countElementsInCollection|applyOnElement|forEachOneIn|onTheCollectionApplyMethod|fetchOnly|fetchAllWith|countTheNumberOfElements|getLastElement|getElement|getFirstElement|getAllKeys|getValuesWithoutKeys|collect|fromTheCollectionOf|run|authenticateUser|denyExternalAccess|allowExternalAccess|grantOnlyAdminAccess|onTable|succeedWith|failWith|makeExternalRequest|getResults|storeAs|assign|usingService|callMethod|withParams|withoutParams|to|from|assignValues|stopAndOutput|help|set|read|beforeSigningIn|beforeSigningUp|beforeQueryingProfile|beforeUpdatingProfile|onSigningIn|onSigningUp|onQueringProfile|afterSigningIn|afterSigningUp|afterQueryingProfile|afterUpdatingProfile|getLastCharacter|countWords|countCharacters".split("|")),
                // u = i.arrayToMap("firstly|$_SERVER|$_GET|$_POST|$_FILES|$_REQUEST|$_SESSION|$_ENV|$_COOKIE|$php_errormsg|$HTTP_RAW_POST_DATA|$http_response_header|$argc|$argv".split("|")),
                a = i.arrayToMap("evaluate|cairo_matrix_create_translate|call_user_method|call_user_method_array|com_addref|com_get|com_invoke|com_isenum|com_load|com_release|com_set|connection_timeout|cubrid_load_from_glo|cubrid_new_glo|cubrid_save_to_glo|cubrid_send_glo|define_syslog_variables|dl|ereg|ereg_replace|eregi|eregi_replace|hw_documentattributes|hw_documentbodytag|hw_documentsize|hw_outputdocument|imagedashedline|maxdb_bind_param|maxdb_bind_result|maxdb_client_encoding|maxdb_close_long_data|maxdb_execute|maxdb_fetch|maxdb_get_metadata|maxdb_param_count|maxdb_send_long_data|mcrypt_ecb|mcrypt_generic_end|mime_content_type|mysql_createdb|mysql_dbname|mysql_db_query|mysql_drop_db|mysql_dropdb|mysql_escape_string|mysql_fieldflags|mysql_fieldflags|mysql_fieldname|mysql_fieldtable|mysql_fieldtype|mysql_freeresult|mysql_listdbs|mysql_list_fields|mysql_listfields|mysql_list_tables|mysql_listtables|mysql_numfields|mysql_numrows|mysql_selectdb|mysql_tablename|mysqli_bind_param|mysqli_bind_result|mysqli_disable_reads_from_master|mysqli_disable_rpl_parse|mysqli_enable_reads_from_master|mysqli_enable_rpl_parse|mysqli_execute|mysqli_fetch|mysqli_get_metadata|mysqli_master_query|mysqli_param_count|mysqli_rpl_parse_enabled|mysqli_rpl_probe|mysqli_rpl_query_type|mysqli_send_long_data|mysqli_send_query|mysqli_slave_query|ocibindbyname|ocicancel|ocicloselob|ocicollappend|ocicollassign|ocicollassignelem|ocicollgetelem|ocicollmax|ocicollsize|ocicolltrim|ocicolumnisnull|ocicolumnname|ocicolumnprecision|ocicolumnscale|ocicolumnsize|ocicolumntype|ocicolumntyperaw|ocicommit|ocidefinebyname|ocierror|ociexecute|ocifetch|ocifetchinto|ocifetchstatement|ocifreecollection|ocifreecursor|ocifreedesc|ocifreestatement|ociinternaldebug|ociloadlob|ocilogoff|ocilogon|ocinewcollection|ocinewcursor|ocinewdescriptor|ocinlogon|ocinumcols|ociparse|ociplogon|ociresult|ocirollback|ocirowcount|ocisavelob|ocisavelobfile|ociserverversion|ocisetprefetch|ocistatementtype|ociwritelobtofile|ociwritetemporarylob|PDF_add_annotation|PDF_add_bookmark|PDF_add_launchlink|PDF_add_locallink|PDF_add_note|PDF_add_outline|PDF_add_pdflink|PDF_add_weblink|PDF_attach_file|PDF_begin_page|PDF_begin_template|PDF_close_pdi|PDF_close|PDF_findfont|PDF_get_font|PDF_get_fontname|PDF_get_fontsize|PDF_get_image_height|PDF_get_image_width|PDF_get_majorversion|PDF_get_minorversion|PDF_get_pdi_parameter|PDF_get_pdi_value|PDF_open_ccitt|PDF_open_file|PDF_open_gif|PDF_open_image_file|PDF_open_image|PDF_open_jpeg|PDF_open_pdi|PDF_open_tiff|PDF_place_image|PDF_place_pdi_page|PDF_set_border_color|PDF_set_border_dash|PDF_set_border_style|PDF_set_char_spacing|PDF_set_duration|PDF_set_horiz_scaling|PDF_set_info_author|PDF_set_info_creator|PDF_set_info_keywords|PDF_set_info_subject|PDF_set_info_title|PDF_set_leading|PDF_set_text_matrix|PDF_set_text_rendering|PDF_set_text_rise|PDF_set_word_spacing|PDF_setgray_fill|PDF_setgray_stroke|PDF_setgray|PDF_setpolydash|PDF_setrgbcolor_fill|PDF_setrgbcolor_stroke|PDF_setrgbcolor|PDF_show_boxed|php_check_syntax|px_set_tablename|px_set_targetencoding|runkit_sandbox_output_handler|session_is_registered|session_register|session_unregisterset_magic_quotes_runtime|magic_quotes_runtime|set_socket_blocking|socket_set_blocking|set_socket_timeout|socket_set_timeout|split|spliti|sql_regcase".split("|")),
                f = i.arrayToMap("cfunction|old_function".split("|")),
                l = i.arrayToMap("moon".split("|"));
            this.$rules = {
                start: [{
                    token: "comment",
                    regex: "\\/\\/.*$"
                }, {
                    token: "comment",
                    regex: "#.*$"
                }, e.getStartRule("doc-start"), {
                    token: "comment",
                    regex: "\\/\\*",
                    next: "comment"
                }, {
                    token: "string.regexp",
                    regex: "[/](?:(?:\\[(?:\\\\]|[^\\]])+\\])|(?:\\\\/|[^\\]/]))*[/][gimy]*\\s*(?=[).,;]|$)"
                }, {
                    token: "string",
                    regex: '"',
                    next: "qqstring"
                }, {
                    token: "string",
                    regex: "'",
                    next: "qstring"
                }, {
                    token: "constant.numeric",
                    regex: "0[xX][0-9a-fA-F]+\\b"
                }, {
                    token: "constant.numeric",
                    regex: "[+-]?\\d+(?:(?:\\.\\d*)?(?:[eE][+-]?\\d+)?)?\\b"
                }, {
                    token: "constant.language",
                    regex: "\\b(?:DEFAULT_INCLUDE_PATH|E_(?:ALL|CO(?:MPILE_(?:ERROR|WARNING)|RE_(?:ERROR|WARNING))|ERROR|NOTICE|PARSE|STRICT|USER_(?:ERROR|NOTICE|WARNING)|WARNING)|P(?:EAR_(?:EXTENSION_DIR|INSTALL_DIR)|HP_(?:BINDIR|CONFIG_FILE_(?:PATH|SCAN_DIR)|DATADIR|E(?:OL|XTENSION_DIR)|INT_(?:MAX|SIZE)|L(?:IBDIR|OCALSTATEDIR)|O(?:S|UTPUT_HANDLER_(?:CONT|END|START))|PREFIX|S(?:API|HLIB_SUFFIX|YSCONFDIR)|VERSION))|__COMPILER_HALT_OFFSET__)\\b"
                }, {
                    token: "constant.language",
                    regex: "\\b(?:A(?:B(?:DAY_(?:1|2|3|4|5|6|7)|MON_(?:1(?:0|1|2|)|2|3|4|5|6|7|8|9))|LT_DIGITS|M_STR|SSERT_(?:ACTIVE|BAIL|CALLBACK|QUIET_EVAL|WARNING))|C(?:ASE_(?:LOWER|UPPER)|HAR_MAX|O(?:DESET|NNECTION_(?:ABORTED|NORMAL|TIMEOUT)|UNT_(?:NORMAL|RECURSIVE))|R(?:EDITS_(?:ALL|DOCS|FULLPAGE|G(?:ENERAL|ROUP)|MODULES|QA|SAPI)|NCYSTR|YPT_(?:BLOWFISH|EXT_DES|MD5|S(?:ALT_LENGTH|TD_DES)))|URRENCY_SYMBOL)|D(?:AY_(?:1|2|3|4|5|6|7)|ECIMAL_POINT|IRECTORY_SEPARATOR|_(?:FMT|T_FMT))|E(?:NT_(?:COMPAT|NOQUOTES|QUOTES)|RA(?:_(?:D_(?:FMT|T_FMT)|T_FMT|YEAR)|)|XTR_(?:IF_EXISTS|OVERWRITE|PREFIX_(?:ALL|I(?:F_EXISTS|NVALID)|SAME)|SKIP))|FRAC_DIGITS|GROUPING|HTML_(?:ENTITIES|SPECIALCHARS)|IN(?:FO_(?:ALL|C(?:ONFIGURATION|REDITS)|ENVIRONMENT|GENERAL|LICENSE|MODULES|VARIABLES)|I_(?:ALL|PERDIR|SYSTEM|USER)|T_(?:CURR_SYMBOL|FRAC_DIGITS))|L(?:C_(?:ALL|C(?:OLLATE|TYPE)|M(?:ESSAGES|ONETARY)|NUMERIC|TIME)|O(?:CK_(?:EX|NB|SH|UN)|G_(?:A(?:LERT|UTH(?:PRIV|))|C(?:ONS|R(?:IT|ON))|D(?:AEMON|EBUG)|E(?:MERG|RR)|INFO|KERN|L(?:OCAL(?:0|1|2|3|4|5|6|7)|PR)|MAIL|N(?:DELAY|EWS|O(?:TICE|WAIT))|ODELAY|P(?:ERROR|ID)|SYSLOG|U(?:SER|UCP)|WARNING)))|M(?:ON_(?:1(?:0|1|2|)|2|3|4|5|6|7|8|9|DECIMAL_POINT|GROUPING|THOUSANDS_SEP)|_(?:1_PI|2_(?:PI|SQRTPI)|E|L(?:N(?:10|2)|OG(?:10E|2E))|PI(?:_(?:2|4)|)|SQRT(?:1_2|2)))|N(?:EGATIVE_SIGN|O(?:EXPR|STR)|_(?:CS_PRECEDES|S(?:EP_BY_SPACE|IGN_POSN)))|P(?:ATH(?:INFO_(?:BASENAME|DIRNAME|EXTENSION)|_SEPARATOR)|M_STR|OSITIVE_SIGN|_(?:CS_PRECEDES|S(?:EP_BY_SPACE|IGN_POSN)))|RADIXCHAR|S(?:EEK_(?:CUR|END|SET)|ORT_(?:ASC|DESC|NUMERIC|REGULAR|STRING)|TR_PAD_(?:BOTH|LEFT|RIGHT))|T(?:HOUS(?:ANDS_SEP|EP)|_FMT(?:_AMPM|))|YES(?:EXPR|STR)|STD(?:IN|OUT|ERR))\\b"
                }, {
                    token: function(e) {
                        return n.hasOwnProperty(e) ? "keyword" : o.hasOwnProperty(e) ? "constant.language" : u.hasOwnProperty(e) ? "variable.language" : l.hasOwnProperty(e) ? "invalid.illegal" : t.hasOwnProperty(e) ? "support.function" : e == "debugger" ? "invalid.deprecated" : e.match(/^(\$[a-zA-Z][a-zA-Z0-9_]*|self|parent)$/) ? "variable" : "identifier"
                    },
                    regex: "[a-zA-Z_$][a-zA-Z0-9_$]*\\b"
                }, {
                    token: "keyword.operator",
                    regex: "!|\\$|%|&|\\*|\\-\\-|\\-|\\+\\+|\\+|~|===|==|=|!=|!==|<=|>=|<<=|>>=|>>>=|<>|<|>|!|&&|\\|\\||\\?\\:|\\*=|%=|\\+=|\\-=|&=|\\^=|\\b(?:in|instanceof|new|delete|typeof|void)"
                }, {
                    token: "lparen",
                    regex: "[[({]"
                }, {
                    token: "rparen",
                    regex: "[\\])}]"
                }, {
                    token: "text",
                    regex: "\\s+"
                }],
                comment: [{
                    token: "comment",
                    regex: ".*?\\*\\/",
                    next: "start"
                }, {
                    token: "comment",
                    regex: ".+"
                }],
                qqstring: [{
                    token: "constant.language.escape",
                    regex: '\\\\(?:[nrtvef\\\\"$]|[0-7]{1,3}|x[0-9A-Fa-f]{1,2})'
                }, {
                    token: "constant.language.escape",
                    regex: /\$[\w\d]+(?:\[[\w\d]+\])?/
                }, {
                    token: "constant.language.escape",
                    regex: /\$\{[^"\}]+\}?/
                }, {
                    token: "string",
                    regex: '"',
                    next: "start"
                }, {
                    token: "string",
                    regex: ".+?"
                }],
                qstring: [{
                    token: "constant.language.escape",
                    regex: "\\\\['\\\\]"
                }, {
                    token: "string",
                    regex: "'",
                    next: "start"
                }, {
                    token: "string",
                    regex: ".+?"
                }]
            }, this.embedRules(s, "doc-", [s.getEndRule("start")])
        };
    r.inherits(a, o);
    var f = function() {
        u.call(this);
        for (var e in this.$rules) this.$rules[e].unshift({
            token: "support.php_tag",
            regex: "<\\?(?:php|\\=)?",
            next: "php-start"
        });
        this.embedRules(a, "php-"), this.$rules["php-start"].unshift({
            token: "support.php_tag",
            regex: "\\?>",
            next: "start"
        })
    };
    r.inherits(f, u), t.PhpHighlightRules = f
}), ace.define("ace/mode/doc_comment_highlight_rules", ["require", "exports", "module", "ace/lib/oop", "ace/mode/text_highlight_rules"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("./text_highlight_rules").TextHighlightRules,
        s = function() {
            this.$rules = {
                start: [{
                    token: "comment.doc.tag",
                    regex: "@[\\w\\d_]+"
                }, {
                    token: "comment.doc",
                    regex: "\\s+"
                }, {
                    token: "comment.doc",
                    regex: "TODO"
                }, {
                    token: "comment.doc",
                    regex: "[^@\\*]+"
                }, {
                    token: "comment.doc",
                    regex: "."
                }]
            }
        };
    r.inherits(s, i), s.getStartRule = function(e) {
        return {
            token: "comment.doc",
            regex: "\\/\\*(?=\\*)",
            next: e
        }
    }, s.getEndRule = function(e) {
        return {
            token: "comment.doc",
            regex: "\\*\\/",
            next: e
        }
    }, t.DocCommentHighlightRules = s
}), ace.define("ace/mode/html_highlight_rules", ["require", "exports", "module", "ace/lib/oop", "ace/lib/lang", "ace/mode/css_highlight_rules", "ace/mode/javascript_highlight_rules", "ace/mode/xml_util", "ace/mode/text_highlight_rules"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("../lib/lang"),
        s = e("./css_highlight_rules").CssHighlightRules,
        o = e("./javascript_highlight_rules").JavaScriptHighlightRules,
        u = e("./xml_util"),
        a = e("./text_highlight_rules").TextHighlightRules,
        f = i.createMap({
            a: "anchor",
            button: "form",
            form: "form",
            img: "image",
            input: "form",
            label: "form",
            script: "script",
            select: "form",
            textarea: "form",
            style: "style",
            table: "table",
            tbody: "table",
            td: "table",
            tfoot: "table",
            th: "table",
            tr: "table"
        }),
        l = function() {
            this.$rules = {
                start: [{
                    token: "text",
                    regex: "<\\!\\[CDATA\\[",
                    next: "cdata"
                }, {
                    token: "xml-pe",
                    regex: "<\\?.*?\\?>"
                }, {
                    token: "comment",
                    regex: "<\\!--",
                    next: "comment"
                }, {
                    token: "xml-pe",
                    regex: "<\\!.*?>"
                }, {
                    token: "meta.tag",
                    regex: "<(?=script\\b)",
                    next: "script"
                }, {
                    token: "meta.tag",
                    regex: "<(?=style\\b)",
                    next: "style"
                }, {
                    token: "meta.tag",
                    regex: "<\\/?",
                    next: "tag"
                }, {
                    token: "text",
                    regex: "\\s+"
                }, {
                    token: "constant.character.entity",
                    regex: "(?:&#[0-9]+;)|(?:&#x[0-9a-fA-F]+;)|(?:&[a-zA-Z0-9_:\\.-]+;)"
                }, {
                    token: "text",
                    regex: "[^<]+"
                }],
                cdata: [{
                    token: "text",
                    regex: "\\]\\]>",
                    next: "start"
                }, {
                    token: "text",
                    regex: "\\s+"
                }, {
                    token: "text",
                    regex: ".+"
                }],
                comment: [{
                    token: "comment",
                    regex: ".*?-->",
                    next: "start"
                }, {
                    token: "comment",
                    regex: ".+"
                }]
            }, u.tag(this.$rules, "tag", "start", f), u.tag(this.$rules, "style", "css-start", f), u.tag(this.$rules, "script", "js-start", f), this.embedRules(o, "js-", [{
                token: "comment",
                regex: "\\/\\/.*(?=<\\/script>)",
                next: "tag"
            }, {
                token: "meta.tag",
                regex: "<\\/(?=script)",
                next: "tag"
            }]), this.embedRules(s, "css-", [{
                token: "meta.tag",
                regex: "<\\/(?=style)",
                next: "tag"
            }])
        };
    r.inherits(l, a), t.HtmlHighlightRules = l
}), ace.define("ace/mode/css_highlight_rules", ["require", "exports", "module", "ace/lib/oop", "ace/lib/lang", "ace/mode/text_highlight_rules"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("../lib/lang"),
        s = e("./text_highlight_rules").TextHighlightRules,
        o = t.supportType = "animation-fill-mode|alignment-adjust|alignment-baseline|animation-delay|animation-direction|animation-duration|animation-iteration-count|animation-name|animation-play-state|animation-timing-function|animation|appearance|azimuth|backface-visibility|background-attachment|background-break|background-clip|background-color|background-image|background-origin|background-position|background-repeat|background-size|background|baseline-shift|binding|bleed|bookmark-label|bookmark-level|bookmark-state|bookmark-target|border-bottom|border-bottom-color|border-bottom-left-radius|border-bottom-right-radius|border-bottom-style|border-bottom-width|border-collapse|border-color|border-image|border-image-outset|border-image-repeat|border-image-slice|border-image-source|border-image-width|border-left|border-left-color|border-left-style|border-left-width|border-radius|border-right|border-right-color|border-right-style|border-right-width|border-spacing|border-style|border-top|border-top-color|border-top-left-radius|border-top-right-radius|border-top-style|border-top-width|border-width|border|bottom|box-align|box-decoration-break|box-direction|box-flex-group|box-flex|box-lines|box-ordinal-group|box-orient|box-pack|box-shadow|box-sizing|break-after|break-before|break-inside|caption-side|clear|clip|color-profile|color|column-count|column-fill|column-gap|column-rule|column-rule-color|column-rule-style|column-rule-width|column-span|column-width|columns|content|counter-increment|counter-reset|crop|cue-after|cue-before|cue|cursor|direction|display|dominant-baseline|drop-initial-after-adjust|drop-initial-after-align|drop-initial-before-adjust|drop-initial-before-align|drop-initial-size|drop-initial-value|elevation|empty-cells|fit|fit-position|float-offset|float|font-family|font-size|font-size-adjust|font-stretch|font-style|font-variant|font-weight|font|grid-columns|grid-rows|hanging-punctuation|height|hyphenate-after|hyphenate-before|hyphenate-character|hyphenate-lines|hyphenate-resource|hyphens|icon|image-orientation|image-rendering|image-resolution|inline-box-align|left|letter-spacing|line-height|line-stacking-ruby|line-stacking-shift|line-stacking-strategy|line-stacking|list-style-image|list-style-position|list-style-type|list-style|margin-bottom|margin-left|margin-right|margin-top|margin|mark-after|mark-before|mark|marks|marquee-direction|marquee-play-count|marquee-speed|marquee-style|max-height|max-width|min-height|min-width|move-to|nav-down|nav-index|nav-left|nav-right|nav-up|opacity|orphans|outline-color|outline-offset|outline-style|outline-width|outline|overflow-style|overflow-x|overflow-y|overflow|padding-bottom|padding-left|padding-right|padding-top|padding|page-break-after|page-break-before|page-break-inside|page-policy|page|pause-after|pause-before|pause|perspective-origin|perspective|phonemes|pitch-range|pitch|play-during|position|presentation-level|punctuation-trim|quotes|rendering-intent|resize|rest-after|rest-before|rest|richness|right|rotation-point|rotation|ruby-align|ruby-overhang|ruby-position|ruby-span|size|speak-header|speak-numeral|speak-punctuation|speak|speech-rate|stress|string-set|table-layout|target-name|target-new|target-position|target|text-align-last|text-align|text-decoration|text-emphasis|text-height|text-indent|text-justify|text-outline|text-shadow|text-transform|text-wrap|top|transform-origin|transform-style|transform|transition-delay|transition-duration|transition-property|transition-timing-function|transition|unicode-bidi|vertical-align|visibility|voice-balance|voice-duration|voice-family|voice-pitch-range|voice-pitch|voice-rate|voice-stress|voice-volume|volume|white-space-collapse|white-space|widows|width|word-break|word-spacing|word-wrap|z-index",
        u = t.supportFunction = "rgb|rgba|url|attr|counter|counters",
        a = t.supportConstant = "absolute|after-edge|after|all-scroll|all|alphabetic|always|antialiased|armenian|auto|avoid-column|avoid-page|avoid|balance|baseline|before-edge|before|below|bidi-override|block-line-height|block|bold|bolder|border-box|both|bottom|box|break-all|break-word|capitalize|caps-height|caption|center|central|char|circle|cjk-ideographic|clone|close-quote|col-resize|collapse|column|consider-shifts|contain|content-box|cover|crosshair|cubic-bezier|dashed|decimal-leading-zero|decimal|default|disabled|disc|disregard-shifts|distribute-all-lines|distribute-letter|distribute-space|distribute|dotted|double|e-resize|ease-in|ease-in-out|ease-out|ease|ellipsis|end|exclude-ruby|fill|fixed|georgian|glyphs|grid-height|groove|hand|hanging|hebrew|help|hidden|hiragana-iroha|hiragana|horizontal|icon|ideograph-alpha|ideograph-numeric|ideograph-parenthesis|ideograph-space|ideographic|inactive|include-ruby|inherit|initial|inline-block|inline-box|inline-line-height|inline-table|inline|inset|inside|inter-ideograph|inter-word|invert|italic|justify|katakana-iroha|katakana|keep-all|last|left|lighter|line-edge|line-through|line|linear|list-item|local|loose|lower-alpha|lower-greek|lower-latin|lower-roman|lowercase|lr-tb|ltr|mathematical|max-height|max-size|medium|menu|message-box|middle|move|n-resize|ne-resize|newspaper|no-change|no-close-quote|no-drop|no-open-quote|no-repeat|none|normal|not-allowed|nowrap|nw-resize|oblique|open-quote|outset|outside|overline|padding-box|page|pointer|pre-line|pre-wrap|pre|preserve-3d|progress|relative|repeat-x|repeat-y|repeat|replaced|reset-size|ridge|right|round|row-resize|rtl|s-resize|scroll|se-resize|separate|slice|small-caps|small-caption|solid|space|square|start|static|status-bar|step-end|step-start|steps|stretch|strict|sub|super|sw-resize|table-caption|table-cell|table-column-group|table-column|table-footer-group|table-header-group|table-row-group|table-row|table|tb-rl|text-after-edge|text-before-edge|text-bottom|text-size|text-top|text|thick|thin|transparent|underline|upper-alpha|upper-latin|upper-roman|uppercase|use-script|vertical-ideographic|vertical-text|visible|w-resize|wait|whitespace|z-index|zero",
        f = t.supportConstantColor = "aqua|black|blue|fuchsia|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|teal|white|yellow",
        l = t.supportConstantFonts = "arial|century|comic|courier|garamond|georgia|helvetica|impact|lucida|symbol|system|tahoma|times|trebuchet|utopia|verdana|webdings|sans-serif|serif|monospace",
        c = t.numRe = "\\-?(?:(?:[0-9]+)|(?:[0-9]*\\.[0-9]+))",
        h = t.pseudoElements = "(\\:+)\\b(after|before|first-letter|first-line|moz-selection|selection)\\b",
        p = t.pseudoClasses = "(:)\\b(active|checked|disabled|empty|enabled|first-child|first-of-type|focus|hover|indeterminate|invalid|last-child|last-of-type|link|not|nth-child|nth-last-child|nth-last-of-type|nth-of-type|only-child|only-of-type|required|root|target|valid|visited)\\b",
        d = function() {
            var e = this.createKeywordMapper({
                    "support.function": u,
                    "support.constant": a,
                    "support.type": o,
                    "support.constant.color": f,
                    "support.constant.fonts": l
                }, "text", !0),
                t = [{
                    token: "comment",
                    regex: "\\/\\*",
                    next: "ruleset_comment"
                }, {
                    token: "string",
                    regex: '["](?:(?:\\\\.)|(?:[^"\\\\]))*?["]'
                }, {
                    token: "string",
                    regex: "['](?:(?:\\\\.)|(?:[^'\\\\]))*?[']"
                }, {
                    token: ["constant.numeric", "keyword"],
                    regex: "(" + c + ")(ch|cm|deg|em|ex|fr|gd|grad|Hz|in|kHz|mm|ms|pc|pt|px|rad|rem|s|turn|vh|vm|vw|%)"
                }, {
                    token: "constant.numeric",
                    regex: c
                }, {
                    token: "constant.numeric",
                    regex: "#[a-f0-9]{6}"
                }, {
                    token: "constant.numeric",
                    regex: "#[a-f0-9]{3}"
                }, {
                    token: ["punctuation", "entity.other.attribute-name.pseudo-element.css"],
                    regex: h
                }, {
                    token: ["punctuation", "entity.other.attribute-name.pseudo-class.css"],
                    regex: p
                }, {
                    token: e,
                    regex: "\\-?[a-zA-Z_][a-zA-Z0-9_\\-]*"
                }],
                n = i.copyArray(t);
            n.unshift({
                token: "paren.rparen",
                regex: "\\}",
                next: "start"
            });
            var r = i.copyArray(t);
            r.unshift({
                token: "paren.rparen",
                regex: "\\}",
                next: "media"
            });
            var s = [{
                    token: "comment",
                    regex: ".+"
                }],
                d = i.copyArray(s);
            d.unshift({
                token: "comment",
                regex: ".*?\\*\\/",
                next: "start"
            });
            var v = i.copyArray(s);
            v.unshift({
                token: "comment",
                regex: ".*?\\*\\/",
                next: "media"
            });
            var m = i.copyArray(s);
            m.unshift({
                token: "comment",
                regex: ".*?\\*\\/",
                next: "ruleset"
            }), this.$rules = {
                start: [{
                    token: "comment",
                    regex: "\\/\\*",
                    next: "comment"
                }, {
                    token: "paren.lparen",
                    regex: "\\{",
                    next: "ruleset"
                }, {
                    token: "string",
                    regex: "@.*?{",
                    next: "media"
                }, {
                    token: "keyword",
                    regex: "#[a-z0-9-_]+"
                }, {
                    token: "variable",
                    regex: "\\.[a-z0-9-_]+"
                }, {
                    token: "string",
                    regex: ":[a-z0-9-_]+"
                }, {
                    token: "constant",
                    regex: "[a-z0-9-_]+"
                }],
                media: [{
                    token: "comment",
                    regex: "\\/\\*",
                    next: "media_comment"
                }, {
                    token: "paren.lparen",
                    regex: "\\{",
                    next: "media_ruleset"
                }, {
                    token: "string",
                    regex: "\\}",
                    next: "start"
                }, {
                    token: "keyword",
                    regex: "#[a-z0-9-_]+"
                }, {
                    token: "variable",
                    regex: "\\.[a-z0-9-_]+"
                }, {
                    token: "string",
                    regex: ":[a-z0-9-_]+"
                }, {
                    token: "constant",
                    regex: "[a-z0-9-_]+"
                }],
                comment: d,
                ruleset: n,
                ruleset_comment: m,
                media_ruleset: r,
                media_comment: v
            }
        };
    r.inherits(d, s), t.CssHighlightRules = d
}), ace.define("ace/mode/javascript_highlight_rules", ["require", "exports", "module", "ace/lib/oop", "ace/mode/doc_comment_highlight_rules", "ace/mode/text_highlight_rules"], function(e, t, n) {
    var r = e("../lib/oop"),
        i = e("./doc_comment_highlight_rules").DocCommentHighlightRules,
        s = e("./text_highlight_rules").TextHighlightRules,
        o = function() {
            var e = this.createKeywordMapper({
                    "variable.language": "Array|Boolean|Date|Function|Iterator|Number|Object|RegExp|String|Proxy|Namespace|QName|XML|XMLList|ArrayBuffer|Float32Array|Float64Array|Int16Array|Int32Array|Int8Array|Uint16Array|Uint32Array|Uint8Array|Uint8ClampedArray|Error|EvalError|InternalError|RangeError|ReferenceError|StopIteration|SyntaxError|TypeError|URIError|decodeURI|decodeURIComponent|encodeURI|encodeURIComponent|eval|isFinite|isNaN|parseFloat|parseInt|JSON|Math|this|arguments|prototype|window|document",
                    keyword: "const|yield|import|get|set|break|case|catch|continue|default|delete|do|else|finally|for|function|if|in|instanceof|new|return|switch|throw|try|typeof|let|var|while|with|debugger|__parent__|__count__|escape|unescape|with|__proto__|class|enum|extends|super|export|implements|private|public|interface|package|protected|static",
                    "storage.type": "const|let|var|function",
                    "constant.language": "null|Infinity|NaN|undefined",
                    "support.function": "alert"
                }, "identifier"),
                t = "case|do|else|finally|in|instanceof|return|throw|try|typeof|yield|void",
                n = "[a-zA-Z\\$_¡-￿][a-zA-Z\\d\\$_¡-￿]*\\b",
                r = "\\\\(?:x[0-9a-fA-F]{2}|u[0-9a-fA-F]{4}|[0-2][0-7]{0,2}|3[0-6][0-7]?|37[0-7]?|[4-7][0-7]?|.)";
            this.$rules = {
                start: [{
                    token: "comment",
                    regex: /\/\/.*$/
                }, i.getStartRule("doc-start"), {
                    token: "comment",
                    regex: /\/\*/,
                    next: "comment"
                }, {
                    token: "string",
                    regex: "'(?=.)",
                    next: "qstring"
                }, {
                    token: "string",
                    regex: '"(?=.)',
                    next: "qqstring"
                }, {
                    token: "constant.numeric",
                    regex: /0[xX][0-9a-fA-F]+\b/
                }, {
                    token: "constant.numeric",
                    regex: /[+-]?\d+(?:(?:\.\d*)?(?:[eE][+-]?\d+)?)?\b/
                }, {
                    token: ["storage.type", "punctuation.operator", "support.function", "punctuation.operator", "entity.name.function", "text", "keyword.operator"],
                    regex: "(" + n + ")(\\.)(prototype)(\\.)(" + n + ")(\\s*)(=)",
                    next: "function_arguments"
                }, {
                    token: ["storage.type", "punctuation.operator", "entity.name.function", "text", "keyword.operator", "text", "storage.type", "text", "paren.lparen"],
                    regex: "(" + n + ")(\\.)(" + n + ")(\\s*)(=)(\\s*)(function)(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: ["entity.name.function", "text", "keyword.operator", "text", "storage.type", "text", "paren.lparen"],
                    regex: "(" + n + ")(\\s*)(=)(\\s*)(function)(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: ["storage.type", "punctuation.operator", "entity.name.function", "text", "keyword.operator", "text", "storage.type", "text", "entity.name.function", "text", "paren.lparen"],
                    regex: "(" + n + ")(\\.)(" + n + ")(\\s*)(=)(\\s*)(function)(\\s+)(\\w+)(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: ["storage.type", "text", "entity.name.function", "text", "paren.lparen"],
                    regex: "(function)(\\s+)(" + n + ")(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: ["entity.name.function", "text", "punctuation.operator", "text", "storage.type", "text", "paren.lparen"],
                    regex: "(" + n + ")(\\s*)(:)(\\s*)(function)(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: ["text", "text", "storage.type", "text", "paren.lparen"],
                    regex: "(:)(\\s*)(function)(\\s*)(\\()",
                    next: "function_arguments"
                }, {
                    token: "constant.language.boolean",
                    regex: /(?:true|false)\b/
                }, {
                    token: "keyword",
                    regex: "(?:" + t + ")\\b",
                    next: "regex_allowed"
                }, {
                    token: ["punctuation.operator", "support.function"],
                    regex: /(\.)(s(?:h(?:ift|ow(?:Mod(?:elessDialog|alDialog)|Help))|croll(?:X|By(?:Pages|Lines)?|Y|To)?|t(?:opzzzz|rike)|i(?:n|zeToContent|debar|gnText)|ort|u(?:p|b(?:str(?:ing)?)?)|pli(?:ce|t)|e(?:nd|t(?:Re(?:sizable|questHeader)|M(?:i(?:nutes|lliseconds)|onth)|Seconds|Ho(?:tKeys|urs)|Year|Cursor|Time(?:out)?|Interval|ZOptions|Date|UTC(?:M(?:i(?:nutes|lliseconds)|onth)|Seconds|Hours|Date|FullYear)|FullYear|Active)|arch)|qrt|lice|avePreferences|mall)|h(?:ome|andleEvent)|navigate|c(?:har(?:CodeAt|At)|o(?:s|n(?:cat|textual|firm)|mpile)|eil|lear(?:Timeout|Interval)?|a(?:ptureEvents|ll)|reate(?:StyleSheet|Popup|EventObject))|t(?:o(?:GMTString|S(?:tring|ource)|U(?:TCString|pperCase)|Lo(?:caleString|werCase))|est|a(?:n|int(?:Enabled)?))|i(?:s(?:NaN|Finite)|ndexOf|talics)|d(?:isableExternalCapture|ump|etachEvent)|u(?:n(?:shift|taint|escape|watch)|pdateCommands)|j(?:oin|avaEnabled)|p(?:o(?:p|w)|ush|lugins.refresh|a(?:ddings|rse(?:Int|Float)?)|r(?:int|ompt|eference))|e(?:scape|nableExternalCapture|val|lementFromPoint|x(?:p|ec(?:Script|Command)?))|valueOf|UTC|queryCommand(?:State|Indeterm|Enabled|Value)|f(?:i(?:nd|le(?:ModifiedDate|Size|CreatedDate|UpdatedDate)|xed)|o(?:nt(?:size|color)|rward)|loor|romCharCode)|watch|l(?:ink|o(?:ad|g)|astIndexOf)|a(?:sin|nchor|cos|t(?:tachEvent|ob|an(?:2)?)|pply|lert|b(?:s|ort))|r(?:ou(?:nd|teEvents)|e(?:size(?:By|To)|calc|turnValue|place|verse|l(?:oad|ease(?:Capture|Events)))|andom)|g(?:o|et(?:ResponseHeader|M(?:i(?:nutes|lliseconds)|onth)|Se(?:conds|lection)|Hours|Year|Time(?:zoneOffset)?|Da(?:y|te)|UTC(?:M(?:i(?:nutes|lliseconds)|onth)|Seconds|Hours|Da(?:y|te)|FullYear)|FullYear|A(?:ttention|llResponseHeaders)))|m(?:in|ove(?:B(?:y|elow)|To(?:Absolute)?|Above)|ergeAttributes|a(?:tch|rgins|x))|b(?:toa|ig|o(?:ld|rderWidths)|link|ack))\b(?=\()/
                }, {
                    token: ["punctuation.operator", "support.function.dom"],
                    regex: /(\.)(s(?:ub(?:stringData|mit)|plitText|e(?:t(?:NamedItem|Attribute(?:Node)?)|lect))|has(?:ChildNodes|Feature)|namedItem|c(?:l(?:ick|o(?:se|neNode))|reate(?:C(?:omment|DATASection|aption)|T(?:Head|extNode|Foot)|DocumentFragment|ProcessingInstruction|E(?:ntityReference|lement)|Attribute))|tabIndex|i(?:nsert(?:Row|Before|Cell|Data)|tem)|open|delete(?:Row|C(?:ell|aption)|T(?:Head|Foot)|Data)|focus|write(?:ln)?|a(?:dd|ppend(?:Child|Data))|re(?:set|place(?:Child|Data)|move(?:NamedItem|Child|Attribute(?:Node)?)?)|get(?:NamedItem|Element(?:sBy(?:Name|TagName)|ById)|Attribute(?:Node)?)|blur)\b(?=\()/
                }, {
                    token: ["punctuation.operator", "support.constant"],
                    regex: /(\.)(s(?:ystemLanguage|cr(?:ipts|ollbars|een(?:X|Y|Top|Left))|t(?:yle(?:Sheets)?|atus(?:Text|bar)?)|ibling(?:Below|Above)|ource|uffixes|e(?:curity(?:Policy)?|l(?:ection|f)))|h(?:istory|ost(?:name)?|as(?:h|Focus))|y|X(?:MLDocument|SLDocument)|n(?:ext|ame(?:space(?:s|URI)|Prop))|M(?:IN_VALUE|AX_VALUE)|c(?:haracterSet|o(?:n(?:structor|trollers)|okieEnabled|lorDepth|mp(?:onents|lete))|urrent|puClass|l(?:i(?:p(?:boardData)?|entInformation)|osed|asses)|alle(?:e|r)|rypto)|t(?:o(?:olbar|p)|ext(?:Transform|Indent|Decoration|Align)|ags)|SQRT(?:1_2|2)|i(?:n(?:ner(?:Height|Width)|put)|ds|gnoreCase)|zIndex|o(?:scpu|n(?:readystatechange|Line)|uter(?:Height|Width)|p(?:sProfile|ener)|ffscreenBuffering)|NEGATIVE_INFINITY|d(?:i(?:splay|alog(?:Height|Top|Width|Left|Arguments)|rectories)|e(?:scription|fault(?:Status|Ch(?:ecked|arset)|View)))|u(?:ser(?:Profile|Language|Agent)|n(?:iqueID|defined)|pdateInterval)|_content|p(?:ixelDepth|ort|ersonalbar|kcs11|l(?:ugins|atform)|a(?:thname|dding(?:Right|Bottom|Top|Left)|rent(?:Window|Layer)?|ge(?:X(?:Offset)?|Y(?:Offset)?))|r(?:o(?:to(?:col|type)|duct(?:Sub)?|mpter)|e(?:vious|fix)))|e(?:n(?:coding|abledPlugin)|x(?:ternal|pando)|mbeds)|v(?:isibility|endor(?:Sub)?|Linkcolor)|URLUnencoded|P(?:I|OSITIVE_INFINITY)|f(?:ilename|o(?:nt(?:Size|Family|Weight)|rmName)|rame(?:s|Element)|gColor)|E|whiteSpace|l(?:i(?:stStyleType|n(?:eHeight|kColor))|o(?:ca(?:tion(?:bar)?|lName)|wsrc)|e(?:ngth|ft(?:Context)?)|a(?:st(?:M(?:odified|atch)|Index|Paren)|yer(?:s|X)|nguage))|a(?:pp(?:MinorVersion|Name|Co(?:deName|re)|Version)|vail(?:Height|Top|Width|Left)|ll|r(?:ity|guments)|Linkcolor|bove)|r(?:ight(?:Context)?|e(?:sponse(?:XML|Text)|adyState))|global|x|m(?:imeTypes|ultiline|enubar|argin(?:Right|Bottom|Top|Left))|L(?:N(?:10|2)|OG(?:10E|2E))|b(?:o(?:ttom|rder(?:Width|RightWidth|BottomWidth|Style|Color|TopWidth|LeftWidth))|ufferDepth|elow|ackground(?:Color|Image)))\b/
                }, {
                    token: ["storage.type", "punctuation.operator", "support.function.firebug"],
                    regex: /(console)(\.)(warn|info|log|error|time|timeEnd|assert)\b/
                }, {
                    token: e,
                    regex: n
                }, {
                    token: "keyword.operator",
                    regex: /--|\+\+|[!$%&*+\-~]|===|==|=|!=|!==|<=|>=|<<=|>>=|>>>=|<>|<|>|!|&&|\|\||\?\:|\*=|%=|\+=|\-=|&=|\^=/,
                    next: "regex_allowed"
                }, {
                    token: "punctuation.operator",
                    regex: /\?|\:|\,|\;|\./,
                    next: "regex_allowed"
                }, {
                    token: "paren.lparen",
                    regex: /[\[({]/,
                    next: "regex_allowed"
                }, {
                    token: "paren.rparen",
                    regex: /[\])}]/
                }, {
                    token: "keyword.operator",
                    regex: /\/=?/,
                    next: "regex_allowed"
                }, {
                    token: "comment",
                    regex: /^#!.*$/
                }, {
                    token: "text",
                    regex: /\s+/
                }],
                regex_allowed: [i.getStartRule("doc-start"), {
                    token: "comment",
                    regex: "\\/\\*",
                    next: "comment_regex_allowed"
                }, {
                    token: "comment",
                    regex: "\\/\\/.*$"
                }, {
                    token: "string.regexp",
                    regex: "\\/",
                    next: "regex"
                }, {
                    token: "text",
                    regex: "\\s+"
                }, {
                    token: "empty",
                    regex: "",
                    next: "start"
                }],
                regex: [{
                    token: "regexp.keyword.operator",
                    regex: "\\\\(?:u[\\da-fA-F]{4}|x[\\da-fA-F]{2}|.)"
                }, {
                    token: "string.regexp",
                    regex: "/\\w*",
                    next: "start"
                }, {
                    token: "invalid",
                    regex: /\{\d+,?(?:\d+)?}[+*]|[+*$^?][+*]|[$^][?]|\?{3,}/
                }, {
                    token: "constant.language.escape",
                    regex: /\(\?[:=!]|\)|{\d+,?(?:\d+)?}|{,\d+}|[+*]\?|[(|)$^+*?]/
                }, {
                    token: "string.regexp",
                    regex: /{|[^{\[\/\\(|)$^+*?]+/
                }, {
                    token: "constant.language.escape",
                    regex: /\[\^?/,
                    next: "regex_character_class"
                }, {
                    token: "empty",
                    regex: "",
                    next: "start"
                }],
                regex_character_class: [{
                    token: "regexp.keyword.operator",
                    regex: "\\\\(?:u[\\da-fA-F]{4}|x[\\da-fA-F]{2}|.)"
                }, {
                    token: "constant.language.escape",
                    regex: "]",
                    next: "regex"
                }, {
                    token: "constant.language.escape",
                    regex: "-"
                }, {
                    token: "string.regexp.charachterclass",
                    regex: /[^\]\-\\]+/
                }, {
                    token: "empty",
                    regex: "",
                    next: "start"
                }],
                function_arguments: [{
                    token: "variable.parameter",
                    regex: n
                }, {
                    token: "punctuation.operator",
                    regex: "[, ]+"
                }, {
                    token: "punctuation.operator",
                    regex: "$"
                }, {
                    token: "empty",
                    regex: "",
                    next: "start"
                }],
                comment_regex_allowed: [{
                    token: "comment",
                    regex: ".*?\\*\\/",
                    next: "regex_allowed"
                }, {
                    token: "comment",
                    regex: ".+"
                }],
                comment: [{
                    token: "comment",
                    regex: ".*?\\*\\/",
                    next: "start"
                }, {
                    token: "comment",
                    regex: ".+"
                }],
                qqstring: [{
                    token: "constant.language.escape",
                    regex: r
                }, {
                    token: "string",
                    regex: "\\\\$",
                    next: "qqstring"
                }, {
                    token: "string",
                    regex: '"|$',
                    next: "start"
                }, {
                    token: "string",
                    regex: ".|\\w+|\\s+"
                }],
                qstring: [{
                    token: "constant.language.escape",
                    regex: r
                }, {
                    token: "string",
                    regex: "\\\\$",
                    next: "qstring"
                }, {
                    token: "string",
                    regex: "'|$",
                    next: "start"
                }, {
                    token: "string",
                    regex: ".|\\w+|\\s+"
                }]
            }, this.embedRules(i, "doc-", [i.getEndRule("start")])
        };
    r.inherits(o, s), t.JavaScriptHighlightRules = o
}), ace.define("ace/mode/xml_util", ["require", "exports", "module"], function(e, t, n) {
    function r(e) {
        return [{
            token: "string",
            regex: '"',
            next: e + "_qqstring"
        }, {
            token: "string",
            regex: "'",
            next: e + "_qstring"
        }]
    }

    function i(e, t) {
        return [{
            token: "string",
            regex: e,
            next: t
        }, {
            token: "constant.language.escape",
            regex: "(?:&#[0-9]+;)|(?:&#x[0-9a-fA-F]+;)|(?:&[a-zA-Z0-9_:\\.-]+;)"
        }, {
            token: "string",
            regex: "\\w+|.|\\s+"
        }]
    }
    t.tag = function(e, t, n, s) {
        e[t] = [{
            token: "text",
            regex: "\\s+"
        }, {
            token: s ? function(e) {
                return s[e] ? "meta.tag.tag-name." + s[e] : "meta.tag.tag-name"
            } : "meta.tag.tag-name",
            regex: "[-_a-zA-Z0-9:]+",
            next: t + "_embed_attribute_list"
        }, {
            token: "empty",
            regex: "",
            next: t + "_embed_attribute_list"
        }], e[t + "_qstring"] = i("'", t + "_embed_attribute_list"), e[t + "_qqstring"] = i('"', t + "_embed_attribute_list"), e[t + "_embed_attribute_list"] = [{
            token: "meta.tag.r",
            regex: "/?>",
            next: n
        }, {
            token: "keyword.operator",
            regex: "="
        }, {
            token: "entity.other.attribute-name",
            regex: "[-_a-zA-Z0-9:]+"
        }, {
            token: "constant.numeric",
            regex: "[+-]?\\d+(?:(?:\\.\\d*)?(?:[eE][+-]?\\d+)?)?\\b"
        }, {
            token: "text",
            regex: "\\s+"
        }].concat(r(t))
    }
}), ace.define("ace/mode/matching_brace_outdent", ["require", "exports", "module", "ace/range"], function(e, t, n) {
    var r = e("../range").Range,
        i = function() {};
    (function() {
        this.checkOutdent = function(e, t) {
            return /^\s+$/.test(e) ? /^\s*\}/.test(t) : !1
        }, this.autoOutdent = function(e, t) {
            var n = e.getLine(t),
                i = n.match(/^(\s*\})/);
            if (!i) return 0;
            var s = i[1].length,
                o = e.findMatchingBracket({
                    row: t,
                    column: s
                });
            if (!o || o.row == t) return 0;
            var u = this.$getIndent(e.getLine(o.row));
            e.replace(new r(t, 0, t, s - 1), u)
        }, this.$getIndent = function(e) {
            var t = e.match(/^(\s+)/);
            return t ? t[1] : ""
        }
    }).call(i.prototype), t.MatchingBraceOutdent = i
}), ace.define("ace/mode/behaviour/cstyle", ["require", "exports", "module", "ace/lib/oop", "ace/mode/behaviour", "ace/token_iterator", "ace/lib/lang"], function(e, t, n) {
    var r = e("../../lib/oop"),
        i = e("../behaviour").Behaviour,
        s = e("../../token_iterator").TokenIterator,
        o = e("../../lib/lang"),
        u = ["text", "paren.rparen", "punctuation.operator"],
        a = ["text", "paren.rparen", "punctuation.operator", "comment"],
        f = 0,
        l = -1,
        c = "",
        h = 0,
        p = -1,
        d = "",
        v = "",
        m = function() {
            m.isSaneInsertion = function(e, t) {
                var n = e.getCursorPosition(),
                    r = new s(t, n.row, n.column);
                if (!this.$matchTokenType(r.getCurrentToken() || "text", u)) {
                    var i = new s(t, n.row, n.column + 1);
                    if (!this.$matchTokenType(i.getCurrentToken() || "text", u)) return !1
                }
                return r.stepForward(), r.getCurrentTokenRow() !== n.row || this.$matchTokenType(r.getCurrentToken() || "text", a)
            }, m.$matchTokenType = function(e, t) {
                return t.indexOf(e.type || e) > -1
            }, m.recordAutoInsert = function(e, t, n) {
                var r = e.getCursorPosition(),
                    i = t.doc.getLine(r.row);
                this.isAutoInsertedClosing(r, i, c[0]) || (f = 0), l = r.row, c = n + i.substr(r.column), f++
            }, m.recordMaybeInsert = function(e, t, n) {
                var r = e.getCursorPosition(),
                    i = t.doc.getLine(r.row);
                this.isMaybeInsertedClosing(r, i) || (h = 0), p = r.row, d = i.substr(0, r.column) + n, v = i.substr(r.column), h++
            }, m.isAutoInsertedClosing = function(e, t, n) {
                return f > 0 && e.row === l && n === c[0] && t.substr(e.column) === c
            }, m.isMaybeInsertedClosing = function(e, t) {
                return h > 0 && e.row === p && t.substr(e.column) === v && t.substr(0, e.column) == d
            }, m.popAutoInsertedClosing = function() {
                c = c.substr(1), f--
            }, m.clearMaybeInsertedClosing = function() {
                h = 0, p = -1
            }, this.add("braces", "insertion", function(e, t, n, r, i) {
                var s = n.getCursorPosition(),
                    u = r.doc.getLine(s.row);
                if (i == "{") {
                    var a = n.getSelectionRange(),
                        f = r.doc.getTextRange(a);
                    if (f !== "" && f !== "{" && n.getWrapBehavioursEnabled()) return {
                        text: "{" + f + "}",
                        selection: !1
                    };
                    if (m.isSaneInsertion(n, r)) return /[\]\}\)]/.test(u[s.column]) ? (m.recordAutoInsert(n, r, "}"), {
                        text: "{}",
                        selection: [1, 1]
                    }) : (m.recordMaybeInsert(n, r, "{"), {
                        text: "{",
                        selection: [1, 1]
                    })
                } else if (i == "}") {
                    var l = u.substring(s.column, s.column + 1);
                    if (l == "}") {
                        var c = r.$findOpeningBracket("}", {
                            column: s.column + 1,
                            row: s.row
                        });
                        if (c !== null && m.isAutoInsertedClosing(s, u, i)) return m.popAutoInsertedClosing(), {
                            text: "",
                            selection: [1, 1]
                        }
                    }
                } else if (i == "\n" || i == "\r\n") {
                    var p = "";
                    m.isMaybeInsertedClosing(s, u) && (p = o.stringRepeat("}", h), m.clearMaybeInsertedClosing());
                    var l = u.substring(s.column, s.column + 1);
                    if (l == "}" || p !== "") {
                        var d = r.findMatchingBracket({
                            row: s.row,
                            column: s.column
                        }, "}");
                        if (!d) return null;
                        var v = this.getNextLineIndent(e, u.substring(0, s.column), r.getTabString()),
                            g = this.$getIndent(u);
                        return {
                            text: "\n" + v + "\n" + g + p,
                            selection: [1, v.length, 1, v.length]
                        }
                    }
                }
            }), this.add("braces", "deletion", function(e, t, n, r, i) {
                var s = r.doc.getTextRange(i);
                if (!i.isMultiLine() && s == "{") {
                    var o = r.doc.getLine(i.start.row),
                        u = o.substring(i.end.column, i.end.column + 1);
                    if (u == "}") return i.end.column++, i;
                    h--
                }
            }), this.add("parens", "insertion", function(e, t, n, r, i) {
                if (i == "(") {
                    var s = n.getSelectionRange(),
                        o = r.doc.getTextRange(s);
                    if (o !== "" && n.getWrapBehavioursEnabled()) return {
                        text: "(" + o + ")",
                        selection: !1
                    };
                    if (m.isSaneInsertion(n, r)) return m.recordAutoInsert(n, r, ")"), {
                        text: "()",
                        selection: [1, 1]
                    }
                } else if (i == ")") {
                    var u = n.getCursorPosition(),
                        a = r.doc.getLine(u.row),
                        f = a.substring(u.column, u.column + 1);
                    if (f == ")") {
                        var l = r.$findOpeningBracket(")", {
                            column: u.column + 1,
                            row: u.row
                        });
                        if (l !== null && m.isAutoInsertedClosing(u, a, i)) return m.popAutoInsertedClosing(), {
                            text: "",
                            selection: [1, 1]
                        }
                    }
                }
            }), this.add("parens", "deletion", function(e, t, n, r, i) {
                var s = r.doc.getTextRange(i);
                if (!i.isMultiLine() && s == "(") {
                    var o = r.doc.getLine(i.start.row),
                        u = o.substring(i.start.column + 1, i.start.column + 2);
                    if (u == ")") return i.end.column++, i
                }
            }), this.add("brackets", "insertion", function(e, t, n, r, i) {
                if (i == "[") {
                    var s = n.getSelectionRange(),
                        o = r.doc.getTextRange(s);
                    if (o !== "" && n.getWrapBehavioursEnabled()) return {
                        text: "[" + o + "]",
                        selection: !1
                    };
                    if (m.isSaneInsertion(n, r)) return m.recordAutoInsert(n, r, "]"), {
                        text: "[]",
                        selection: [1, 1]
                    }
                } else if (i == "]") {
                    var u = n.getCursorPosition(),
                        a = r.doc.getLine(u.row),
                        f = a.substring(u.column, u.column + 1);
                    if (f == "]") {
                        var l = r.$findOpeningBracket("]", {
                            column: u.column + 1,
                            row: u.row
                        });
                        if (l !== null && m.isAutoInsertedClosing(u, a, i)) return m.popAutoInsertedClosing(), {
                            text: "",
                            selection: [1, 1]
                        }
                    }
                }
            }), this.add("brackets", "deletion", function(e, t, n, r, i) {
                var s = r.doc.getTextRange(i);
                if (!i.isMultiLine() && s == "[") {
                    var o = r.doc.getLine(i.start.row),
                        u = o.substring(i.start.column + 1, i.start.column + 2);
                    if (u == "]") return i.end.column++, i
                }
            }), this.add("string_dquotes", "insertion", function(e, t, n, r, i) {
                if (i == '"' || i == "'") {
                    var s = i,
                        o = n.getSelectionRange(),
                        u = r.doc.getTextRange(o);
                    if (u !== "" && u !== "'" && u != '"' && n.getWrapBehavioursEnabled()) return {
                        text: s + u + s,
                        selection: !1
                    };
                    var a = n.getCursorPosition(),
                        f = r.doc.getLine(a.row),
                        l = f.substring(a.column - 1, a.column);
                    if (l == "\\") return null;
                    var c = r.getTokens(o.start.row),
                        h = 0,
                        p, d = -1;
                    for (var v = 0; v < c.length; v++) {
                        p = c[v], p.type == "string" ? d = -1 : d < 0 && (d = p.value.indexOf(s));
                        if (p.value.length + h > o.start.column) break;
                        h += c[v].value.length
                    }
                    if (!p || d < 0 && p.type !== "comment" && (p.type !== "string" || o.start.column !== p.value.length + h - 1 && p.value.lastIndexOf(s) === p.value.length - 1)) {
                        if (!m.isSaneInsertion(n, r)) return;
                        return {
                            text: s + s,
                            selection: [1, 1]
                        }
                    }
                    if (p && p.type === "string") {
                        var g = f.substring(a.column, a.column + 1);
                        if (g == s) return {
                            text: "",
                            selection: [1, 1]
                        }
                    }
                }
            }), this.add("string_dquotes", "deletion", function(e, t, n, r, i) {
                var s = r.doc.getTextRange(i);
                if (!i.isMultiLine() && (s == '"' || s == "'")) {
                    var o = r.doc.getLine(i.start.row),
                        u = o.substring(i.start.column + 1, i.start.column + 2);
                    if (u == '"') return i.end.column++, i
                }
            })
        };
    r.inherits(m, i), t.CstyleBehaviour = m
}), ace.define("ace/mode/folding/cstyle", ["require", "exports", "module", "ace/lib/oop", "ace/range", "ace/mode/folding/fold_mode"], function(e, t, n) {
    var r = e("../../lib/oop"),
        i = e("../../range").Range,
        s = e("./fold_mode").FoldMode,
        o = t.FoldMode = function() {};
    r.inherits(o, s),
        function() {
            this.foldingStartMarker = /(\{|\[)[^\}\]]*$|^\s*(\/\*)/, this.foldingStopMarker = /^[^\[\{]*(\}|\])|^[\s\*]*(\*\/)/, this.getFoldWidgetRange = function(e, t, n) {
                var r = e.getLine(n),
                    i = r.match(this.foldingStartMarker);
                if (i) {
                    var s = i.index;
                    return i[1] ? this.openingBracketBlock(e, i[1], n, s) : e.getCommentFoldRange(n, s + i[0].length, 1)
                }
                if (t !== "markbeginend") return;
                var i = r.match(this.foldingStopMarker);
                if (i) {
                    var s = i.index + i[0].length;
                    return i[1] ? this.closingBracketBlock(e, i[1], n, s) : e.getCommentFoldRange(n, s, -1)
                }
            }
        }.call(o.prototype)
})