USE inclusive_rwanda;

-- Insert default admin user
-- Username: admin, Password: admin123 (CHANGE THIS AFTER FIRST LOGIN!)
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@inclusiverw.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note: This is a hashed version of 'admin123'

-- Insert sample services
INSERT INTO services (title, description, icon) VALUES
('Accessibility Audits', 'We conduct comprehensive accessibility audits for buildings, websites, and digital products to ensure compliance with international standards.', '♿'),
('Training Programs', 'Professional training programs for organizations on disability inclusion, accessible design, and creating inclusive workplaces.', '📚'),
('Assistive Technology', 'Providing access to and training on assistive technologies that empower persons with disabilities.', '💻'),
('Advocacy Services', 'Legal and policy advocacy to promote disability rights and ensure equal opportunities for all.', '⚖️');

-- Insert sample partners
INSERT INTO partners (name, description, website) VALUES
('Rwanda National Union of the Deaf', 'Working together to promote sign language and deaf culture in Rwanda.', 'https://example.com'),
('National Council of Persons with Disabilities', 'Collaborating to advance disability rights and inclusion policies.', 'https://example.com'),
('Ministry of Local Government', 'Partnership to ensure accessibility in public infrastructure and services.', 'https://example.com');

-- Insert sample news articles
INSERT INTO news (title, content, author, published) VALUES
('New Accessibility Guidelines Launched', 'Inclusive Rwanda is proud to announce the launch of comprehensive accessibility guidelines for public buildings in Rwanda. These guidelines will help architects and builders create spaces that are accessible to everyone, including persons with disabilities. The guidelines cover ramps, doorways, signage, and emergency exits.', 'Admin', 1),
('Successful Training Workshop Completed', 'Over 50 participants from various organizations attended our two-day workshop on creating inclusive workplaces. The workshop covered topics including disability awareness, reasonable accommodations, and accessible communication. Feedback from participants has been overwhelmingly positive.', 'Admin', 1),
('Partnership with Technology Companies', 'We have formed new partnerships with leading technology companies to promote digital accessibility. This collaboration will focus on making websites, mobile apps, and software more accessible to persons with disabilities. Training sessions for developers will begin next month.', 'Admin', 1);