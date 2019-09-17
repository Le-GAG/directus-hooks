# Le GAG - Directus hooks

[Directus][directus] hooks to add functionality needed to run Le GAG's backend.


## Installation

Clone this repository in a _LeGAG_ subdirectory of the 
_public/extensions/custom/hooks/_ directory of a [Directus API][directus-api]
instance.


## Hooks

### BeforeInsertCommandesProduitsVariantes

Called before creating an item in the `_commandes_produits_variantes`
collection.

It augments the records with the current price of the corresponding product 
variant.
 
[directus]:     https://directus.io/
[directus-api]: https://github.com/directus/api
