for name in screenshots/*.png; do convert $name screenshots/`basename $name .png`.pdf; done
pdftk screenshots/*.pdf cat output Presentation.pdf
