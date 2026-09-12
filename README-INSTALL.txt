KHOJI THEME — install instructions
====================================

1. Unzip this folder.
2. Rename the unzipped folder to exactly:  khoji
3. Copy that "khoji" folder into your LocalWP site's themes directory:

   In LocalWP: click your "khoji" site -> "Go to site folder" (or similar
   button) -> then go into:  app/public/wp-content/themes/

   Drop the "khoji" theme folder in there, next to twentytwentyfour etc.

4. In wp-admin: Appearance -> Themes -> find "Khoji" -> Activate.

5. Set up product categories (these become your brand tiles):
   Products -> Categories -> Add New
   Create: Nike, Adidas, New Balance, Puma, Reebok, Vans
   Give each one a "thumbnail" image (a shoe photo, not a logo).

6. Add a product:
   Products -> Add New
   - Set Product data -> Variable product
   - Under Attributes: add attribute "Size", values like
     "UK 6 / EU 39", "UK 7 / EU 41" etc, tick "Used for variations"
   - Under Variations: generate variations, set stock per size
   - Product image = side profile photo
   - Product gallery, first image = top-down photo (this is what
     shows on hover, matching the design)
   - Assign it to a category (the brand)

7. Set the homepage hero photo:
   Appearance -> Customize -> Homepage Hero -> upload image -> Publish

8. Add the shop-page filters:
   Appearance -> Widgets -> find "Khoji Shop Filters" area ->
   add "Filter Products by Category" and "Filter Products by Attribute"
   (choose the Size attribute) widgets there.

That's the whole loop. From here, adding a shoe is just step 6 —
no code, no file uploads by hand, no calling the developer.
