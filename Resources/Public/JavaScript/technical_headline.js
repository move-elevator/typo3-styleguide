class TechnicalHeadline {
  constructor() {
    this.init()

    document.addEventListener("page:enter", () => {
      this.init()
    })
  }

  init() {
    if (this.cacheDom()) {
      this.generateTableOfContents()
    }
  }

  cacheDom() {
    const technicalHeadlineElement = document.querySelectorAll(
      ".typo3-styleguide-technical-headline"
    )
    const firstElement = technicalHeadlineElement[0]

    if (!technicalHeadlineElement.length || !firstElement) {
      return false
    }

    this.firstEl = firstElement
    this.technicalHeadlineEl = technicalHeadlineElement

    return true
  }

  generateTableOfContents() {
    if (document.querySelector("#typo3-styleguide-technical-headline-toc")) {
      return
    }
    const tocLabel = this.firstEl.getAttribute("data-label-toc")

    let html = `
      <div id="typo3-styleguide-technical-headline-toc" class="typo3-styleguide-technical-headline-toc">
        <h2 class="typo3-styleguide-technical-headline-toc__headline">${tocLabel}</h2>
        <ul class="typo3-styleguide-technical-headline-toc__items">
    `

    this.technicalHeadlineEl.forEach(headlineEl => {
      const firstChild = headlineEl.firstElementChild
      const tag = firstChild && firstChild.tagName ? firstChild.tagName.toLowerCase() : 'h2'
      const title = headlineEl.querySelector(".typo3-styleguide-technical-headline__title")
        ?.innerHTML
      html += `<li class="typo3-styleguide-technical-headline-toc__item typo3-styleguide-technical-headline-toc__item--${tag}"><a class="typo3-styleguide-technical-headline-toc__link" href="#${headlineEl.getAttribute(
        "id"
      )}">${title}</a></li>`
    })
    html += "</ul></div>"

    // In the frame the table of contents leads the page, also when an image comes before the first headline. Only a header image of the page stays above it
    const main = document.querySelector(".typo3-styleguide-frame__main")
    const headerImage = main?.querySelector(":scope > .typo3-styleguide-frame__header-image")
    if (headerImage) {
      headerImage.insertAdjacentHTML("afterend", html)
    } else if (main) {
      main.insertAdjacentHTML("afterbegin", html)
    } else {
      this.firstEl?.insertAdjacentHTML("beforebegin", html)
    }
  }
}

new TechnicalHeadline()
