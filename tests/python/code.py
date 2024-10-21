# exo: nombres paires
#
nombre = int(input("Entrez un nombre: "))
nombres_pairs = []

for i in range(0, nombre):
    num = int(input("Entrez le {}e nombre: ".format(i+1)))
    if num%2 == 0:
        nombres_pairs.append(num)

print("Les nombres pairs sont: ")

for nombre_pair in nombres_pairs:
    print(f"- {nombre_pair}")

# TVA

# price = int(input("Entrez le prix du produit: "))

# while True:
#     category = input("Entrez la catégorie du produit: ")

#     if category.upper().rstrip() in ["A", "B", "C"]:
#         break
#     print("Mauvaise catégorie. Les categories disponibles sont: 'A', 'B' et 'C'")
 
# if category == "A":
#     taux = 0.07
# elif category == "B":
#     taux = 0.2
# elif category == "C":
#     taux = 0.25

# print("Le prix toute taxe comprise de la catégorie {categorie} est {ttc}".format(
#     categorie=  category,
#     ttc= price*(1+taux)
# ))