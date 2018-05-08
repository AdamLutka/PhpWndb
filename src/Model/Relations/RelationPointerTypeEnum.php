<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

/**
 * @method static $this ANTONYM()
 * @method static $this HYPERNYM()
 * @method static $this INSTANCE_HYPERNYM()
 * @method static $this HYPONYM()
 * @method static $this INSTANCE_HYPONYM()
 * @method static $this MEMBER_HOLONYM()
 * @method static $this SUBSTANCE_HOLONYM()
 * @method static $this PART_HOLONYM()
 * @method static $this MEMBER_MERONYM()
 * @method static $this SUBSTANCE_MERONYM()
 * @method static $this PART_MERONYM()
 * @method static $this ATTRIBUTE()
 * @method static $this DERIVATIONALLY_RELATED_FORM()
 * @method static $this DOMAIN_OF_SYNSET_TOPIC()
 * @method static $this MEMBER_OF_THIS_DOMAIN_TOPIC()
 * @method static $this DOMAIN_OF_SYNSET_REGION()
 * @method static $this MEMBER_OF_THIS_DOMAIN_REGION()
 * @method static $this DOMAIN_OF_SYNSET_USAGE()
 * @method static $this MEMBER_OF_THIS_DOMAIN_USAGE()
 *
 * @method static $this ENTAILMENT()
 * @method static $this CAUSE()
 * @method static $this ALSO_SEE()
 * @method static $this VERB_GROUP()
 *
 * @method static $this SIMILAR_TO()
 * @method static $this PARTICIPLE_OF_VERB()
 * @method static $this PERTAINYM()
 *
 * @method static $this DERIVED_FROM_ADJECTIVE()
 *
 * @method static $this DOMAIN_OF_SYNSET()
 * @method static $this MEMBER_OF_THIS_DOMAIN()
 */
class RelationPointerTypeEnum extends \AL\PhpEnum\Enum {}