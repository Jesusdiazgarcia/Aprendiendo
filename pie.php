</tbody>
        </table>
    </div>

    <!-- Footer Moderno -->
    <footer class="footer-modern">
        <div class="footer-content">
            <div class="container">
                <div class="row g-4">
                    <!-- Información de la empresa -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <div class="footer-brand">
                                <h3 class="footer-title">
                                    <span class="brillante">FCStore</span>
                                </h3>
                                <p class="footer-description">
                                    Tu tienda de confianza para camisetas de fútbol oficiales. 
                                    Ofrecemos la mejor calidad y los diseños más auténticos de los equipos más importantes del mundo.
                                </p>
                            </div>
                            <div class="footer-social">
                                <h6 class="social-title">Síguenos</h6>
                                <div class="social-links">
                                    <a href="#" class="social-link" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="social-link" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="social-link" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="#" class="social-link" title="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enlaces rápidos -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h6 class="footer-heading">Enlaces Rápidos</h6>
                            <ul class="footer-links">
                                <li><a href="index.php">Inicio</a></li>
                                <li><a href="catalogo.php">Catálogo</a></li>
                                <li><a href="boleta.php">Carrito</a></li>
                                <li><a href="login.php">Mi Cuenta</a></li>
                                <li><a href="registro.php">Registrarse</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Categorías -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h6 class="footer-heading">Categorías</h6>
                            <ul class="footer-links">
                                <li><a href="index.php?categoria=local">Local</a></li>
                                <li><a href="index.php?categoria=visitante">Visitante</a></li>
                                <li><a href="index.php?categoria=alternativa">Alternativa</a></li>
                                <li><a href="catalogo.php">Ver Todas</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Información de contacto -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h6 class="footer-heading">Contacto</h6>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <span class="contact-label">Email</span>
                                        <a href="mailto:info@fcstore.com">info@fcstore.com</a>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <span class="contact-label">Teléfono</span>
                                        <a href="tel:+1234567890">+1 (234) 567-890</a>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <span class="contact-label">Dirección</span>
                                        <span>123 Calle Principal, Ciudad, País</span>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <span class="contact-label">Horarios</span>
                                        <span>Lun - Vie: 9:00 - 18:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Línea divisoria -->
                <div class="footer-divider"></div>

                <!-- Footer inferior -->
                <div class="footer-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="copyright">
                                &copy; <?php echo date("Y"); ?> <strong>FCStore</strong> - Todos los derechos reservados.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-legal">
                                <a href="#" class="legal-link">Términos y Condiciones</a>
                                <a href="#" class="legal-link">Política de Privacidad</a>
                                <a href="#" class="legal-link">Política de Devoluciones</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php mysqli_close($conn);?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/mis_scripts.js"></script>
</body>
</html>