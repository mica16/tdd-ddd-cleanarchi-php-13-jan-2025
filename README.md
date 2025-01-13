# UberTop SAAS!

L'application Uber-Like qui permet de réserver un Driver instantanément !

# User Story - Réserver une course

En tant que **Rider**,
Je souhaite **réserver une course** pouvant m'amener à ma destination
De sorte à assurer une alternative efficace aux transports en commun.

Un Rider peut réserver une course à tout moment pour n'importe quelle destination.
La réservation est confirmée une fois qu'un Driver libre est assigné par le système.

# Règles de Gestion de la Tarification

### 1. Prix de Base par Zone Géographique
- **Paris vers l'extérieur** : 20€.
- **Extérieur vers Paris** : 50€.
- **Extérieur vers extérieur** : 100€.
- **Paris intra-muros (trajet à l'intérieur de Paris)** : 30€.

### 2. Frais Kilométriques
- Un supplément de **0,5€ par kilomètre** parcouru est ajouté au prix de base du trajet.
- Les frais kilométriques sont calculés uniquement sur la distance parcourue, sans tenir compte des bouchons ou autres ralentissements.

### 3. Option UberX
- **Description** : UberX permet d'accéder à des véhicules de type berline haut de gamme.
- **Supplément** : Un supplément de **10€** est ajouté pour bénéficier du mode UberX.
- **Condition de Distance** : Le mode UberX est disponible uniquement pour les trajets de **3 kilomètres ou plus**. Pour les trajets inférieurs à cette distance, l'option est refusée.
- **Offre Anniversaire** : Si le jour de la course correspond à l'anniversaire du Rider, le supplément de 10€ pour l'option UberX est **offert** pour les trajets de 3 kilomètres ou plus.

### 4. Limitation du Nombre de Courses par Jour
- **Forfait BASIC** : Un Rider avec le forfait BASIC (ou FREE) est limité à **2 courses par jour** maximum.
- **Forfait PREMIUM** : Un Rider avec le forfait PREMIUM peut effectuer jusqu'à **4 courses par jour**.
- Toute tentative de réservation au-delà de ces limites sera refusée, avec un message informant l'utilisateur qu'il a atteint la limite quotidienne de courses.

### 5. Restriction sur les Réservations
- Un Rider **ne peut réserver qu'une seule course à la fois**.
- Il est impossible de réserver une nouvelle course tant que la course en cours n'est pas terminée ou annulée.

## Règles d'assignation d'un Driver

- Le système recherche un driver disponible dans un rayon de 5km autour du Rider.
- Un Driver ne peut être assigné qu'à une seule réservation à la fois.

# User Story - Lister toutes mes courses passées

En tant que **Rider**,
Je souhaite **lister tout l'historique de mes courses avec mention des Drivers respectifs**
De sorte à pouvoir me figurer la fréquence de mon utilisation.
