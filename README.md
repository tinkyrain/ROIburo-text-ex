# ROIburo-text-ex

# WARNING! 

Т.к. уже не могу быть за компьютером и поправить, то напишу тут. 

Надо декомпозировать ```ProductRepositiry``` на ```GroupRepository``` . Ибо это 2 отдельные сущности. 

Декомпозировать их нужно для того, чтобы не перезагружать класс лишними методами, + это интуитивно не понятно, ибо откуда в репозитории для продуктов появились группы? 

Также нужно разделить сервисы!!! 

Понимаю, что для тестового это достаточно грубая ошибка, но прошу смиловаться ;(
