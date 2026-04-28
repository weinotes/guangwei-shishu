/**
 * 光卫诗书主题 - JavaScript
 *
 * @package GuangweiShishu
 * @author Davey <wgwcko@gmail.com>
 * @license GPL-2.0-or-later
 */

(function() {
    'use strict';

    // DOM加载完成后执行
    document.addEventListener('DOMContentLoaded', function() {
        // 平滑滚动
        initSmoothScroll();
        
        // 导航栏滚动效果
        initNavbarScroll();
        
        // 图片懒加载增强
        initLazyLoad();
        
        // 打印优化
        initPrintOptimization();
    });

    /**
     * 平滑滚动
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * 导航栏滚动效果
     */
    function initNavbarScroll() {
        const header = document.querySelector('.shishu-header');
        if (!header) return;

        let lastScroll = 0;
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            // 添加/移除滚动样式
            if (currentScroll > scrollThreshold) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
            
            lastScroll = currentScroll;
        }, { passive: true });
    }

    /**
     * 图片懒加载增强
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        img.classList.add('is-loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * 打印优化
     */
    function initPrintOptimization() {
        window.addEventListener('beforeprint', function() {
            document.body.classList.add('is-printing');
        });

        window.addEventListener('afterprint', function() {
            document.body.classList.remove('is-printing');
        });
    }

    /**
     * 复制到剪贴板
     */
    window.shishuCopyToClipboard = function(text, button) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopyFeedback(button, '已复制');
            }).catch(function() {
                fallbackCopy(text, button);
            });
        } else {
            fallbackCopy(text, button);
        }
    };

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            showCopyFeedback(button, '已复制');
        } catch (err) {
            showCopyFeedback(button, '复制失败');
        }
        
        document.body.removeChild(textarea);
    }

    function showCopyFeedback(button, message) {
        const originalText = button.textContent;
        button.textContent = message;
        button.disabled = true;
        
        setTimeout(function() {
            button.textContent = originalText;
            button.disabled = false;
        }, 2000);
    }

})();