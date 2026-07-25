import os
import glob

# The directory containing HTML files
dir_path = r"c:\Users\jayar\OneDrive\00 Fortyseven Digital\Clients\We One Removals\website\weoneremovals.com"

# The menu text to replace
menu_search = """                                <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="contact.html">Contact Us</a></li>"""
menu_replace = """                                <li class="nav-item"><a class="nav-link" href="faqs.html">FAQs</a></li>
                                <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="contact.html">Contact Us</a></li>"""

# The footer text to replace
footer_search = """                            <li><a href="packing-service.html">Packing Service</a></li>
                            <li><a href="contact.html">Contact Us</a></li>"""
footer_replace = """                            <li><a href="packing-service.html">Packing Service</a></li>
                            <li><a href="faqs.html">FAQs</a></li>
                            <li><a href="contact.html">Contact Us</a></li>"""

html_files = glob.glob(os.path.join(dir_path, "*.html"))
for file_path in html_files:
    with open(file_path, "r", encoding="utf-8") as f:
        content = f.read()
    
    modified = False
    if menu_search in content:
        content = content.replace(menu_search, menu_replace)
        modified = True
    else:
        print(f"Warning: Menu text not found in {os.path.basename(file_path)}")

    if footer_search in content:
        content = content.replace(footer_search, footer_replace)
        modified = True
    else:
        print(f"Warning: Footer text not found in {os.path.basename(file_path)}")
        
    if modified:
        with open(file_path, "w", encoding="utf-8", newline='') as f:
            f.write(content)
        print(f"Updated {os.path.basename(file_path)}")
